<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendTelegramSaleAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $botToken = config('app.telegram_bot_token', env('TELEGRAM_BOT_TOKEN'));
        $chatId = config('app.telegram_chat_id', env('TELEGRAM_CHAT_ID'));

        if (!$botToken || !$chatId) {
            Log::error('Telegram Bot Token ឬ Chat ID មិនទាន់បានកំណត់ក្នុង .env');
            return;
        }

        // ✅ ជំហានទី១៖ Load ទំនាក់ទំនងទាំងអស់ដែលត្រូវការ
        try {
            $this->order->load('customer', 'user', 'orderDetails.product');
        } catch (\Exception $e) {
            Log::error('បរាជ័យក្នុងការ Load Order Relationships៖ ' . $e->getMessage());
            return; // បញ្ឈប់ Job បើរក Relationship មិនឃើញ
        }

        // ✅ ជំហានទី២៖ រៀបចំអថេរ (Variables)
        $customerName = $this->order->customer ? $this->order->customer->name : 'Walk-In';
        $cashierName = $this->order->user ? $this->order->user->name : 'N/A';
        // ប្រើ Carbon::parse()->setTimezone() ដើម្បីធានាថាត្រូវតំបន់ម៉ោង
        $orderDate = Carbon::parse($this->order->created_at)->setTimezone('Asia/Phnom_Penh')->format('d-M-Y H:i A');
        $invoiceNo = $this->order->invoice_no;

        // ✅ ជំហានទី៣៖ បង្កើតសារ (Message String) [បានកែសម្រួល]
        
        // --- ផ្នែកក្បាល (Header) ---

        // កំណត់ប្រភេទ Order
        $orderTypeIcon = '';
        $orderTypeName = '';

        if ($this->order->order_type === 'pre_order') {
            // 1. ករណី Pre-Order (ទំនិញមិនទាន់មាន)
            $orderTypeIcon = '📦';
            $orderTypeName = 'កក់ទុក (Pre-Order)';
        
        } elseif ($this->order->order_type === 'sale') {
            // 2. ករណី Sale (ទំនិញមាន)
            if ($this->order->due > 0) {
                // 2a. Sale តែនៅជំពាក់លុយ (កក់)
                $orderTypeIcon = '⚠️'; // ប្រើ Icon ផ្សេង (Pending/Warning)
                $orderTypeName = 'ការលក់ជំពាក់ (Sale - Due)';
            } else {
                // 2b. Sale ដែលបង់លុយគ្រប់ (រួចរាល់)
                $orderTypeIcon = '🎉';
                $orderTypeName = 'ការលក់ថ្មី (New Sale)';
            }
        } else {
            // Fallback (ករណីផ្សេងៗ)
            $orderTypeIcon = '🧾';
            $orderTypeName = $this->order->order_type; 
        }

        // --- ផ្នែកក្បាល (Header) ---
        // ឥឡូវវានឹងបង្ហាញ Title ទៅតាម ៣ ករណីខាងលើ
        $message = "<b>{$orderTypeIcon} {$orderTypeName} {$orderTypeIcon}</b>\n";
        $message .= "====================\n\n";

        $message .= "🧾 <b>Invoice:</b> <code>" . htmlspecialchars($invoiceNo) . "</code>\n";
        $message .= "🧑 <b>Customer:</b> " . htmlspecialchars($customerName) . "\n";
        $message .= "🧑‍💻 <b>Cashier:</b> " . htmlspecialchars($cashierName) . "\n";
        $message .= "⏰ <b>Time:</b> " . $orderDate . "\n";


        // --- ផ្នែកតារាងទំនិញ (Item List) ---
        $message .= "\n<b>🛒 ទំនិញដែលបានលក់ (Items Sold)</b>\n";

        if ($this->order->orderDetails->isEmpty()) {
            $message .= "<i>(មិនមានទំនិញ)</i>\n";
        } else {
            // កំណត់ទំហំ Column នីមួយៗ
            $col_no = 3;     // "No."
            $col_prod = 10;  // "Product" (កាត់ខ្លីបើវែងពេក)
            $col_qty = 3;    // "Qty"
            $col_price = 10; // "Price"
            $col_total = 10; // "Total"

            // បង្កើត Header របស់តារាង
            $itemsBlock = str_pad("No.", $col_no) . " ";
            $itemsBlock .= str_pad("Product", $col_prod) . " ";
            $itemsBlock .= str_pad("Qty", $col_qty, " ", STR_PAD_LEFT) . " ";
            $itemsBlock .= str_pad("Price", $col_price, " ", STR_PAD_LEFT) . " ";

            // បន្ទាត់ផ្តាច់
            $itemsBlock .= str_repeat("-", $col_no + $col_prod + $col_qty + $col_price + 4) . "\n";

            $index = 1;
            foreach ($this->order->orderDetails as $detail) {
                $productName = $detail->product ? $detail->product->product_name : 'Unknown Product';
                $qty = $detail->quantity;
                $price = number_format($detail->unitcost, 2, '.', ',');
                $lineTotal = number_format($detail->total, 2, '.', ',');

                // កាត់ឈ្មោះ Product បើវែងជាងទំហំ Column
                if (mb_strlen($productName) > $col_prod) {
                    $productName = mb_substr($productName, 0, $col_prod - 3) . "...";
                }

                // បង្កើតបន្ទាត់ទំនិញនីមួយៗ ដោយប្រើ str_pad()
                $line = str_pad($index . ".", $col_no, " ", STR_PAD_RIGHT) . " ";
                $line .= str_pad($productName, $col_prod, " ", STR_PAD_RIGHT) . " ";
                $line .= str_pad($qty, $col_qty, " ", STR_PAD_LEFT) . " ";
                $line .= str_pad("\${$price}", $col_price, " ", STR_PAD_LEFT) . "\n";
                
                $itemsBlock .= $line;
                $index++;
            }

            // ដាក់តារាងទាំងមូលក្នុង <pre> ដើម្បីរក្សាគម្លាត (fixed-width)
            // ប្រើ htmlspecialchars() ដើម្បីសុវត្ថិភាព ក្នុងករណីឈ្មោះទំនិញមានតួអក្សរពិសេស
            $message .= "<pre>" . htmlspecialchars($itemsBlock) . "</pre>";
        }

        // --- ផ្នែកទូទាត់ (Payment Summary) ---
        $subTotal = number_format($this->order->sub_total, 2, '.', ',');
        $discount = number_format($this->order->discount, 2, '.', ',');
        $total = number_format($this->order->total, 2, '.', ',');
        $pay = number_format($this->order->pay, 2, '.', ',');
        $due = number_format($this->order->due, 2, '.', ',');

        $message .= "\n<b>💳 ការទូទាត់ (Payment):</b>\n";
        // កែសម្រួលឲ្យដូចរូបភាពគំរូ (មិនបាច់មាន space_padding)
        $message .= "Subtotal: <code>\${$subTotal}</code>\n";
        $message .= "Discount: <code>-\${$discount}</code>\n";
        $message .= "Total: <code>\${$total}</code>\n";
        $message .= "Paid: <code>\${$pay}</code>\n";
        
        // បង្ហាញ Due តែបើមានជំពាក់ (កូដចាស់ត្រឹមត្រូវហើយ)
        if ($this->order->due > 0) {
            $message .= "<b>Due: <code>\${$due}</code></b>\n";
        }

        // ✅ ជំហានទី៤៖ ផ្ញើសារ (មិនផ្លាស់ប្តូរ)
        try {
            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

        } catch (\Exception $e) {
            Log::error('បរាជ័យក្នុងការផ្ញើ Telegram Alert: ' . $e->getMessage());
        }
    }
}
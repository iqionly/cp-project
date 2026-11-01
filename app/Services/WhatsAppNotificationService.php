<?php

namespace App\Services;

use App\Models\Contribution\Invoice;
use App\Models\Contribution\InvoiceReminder;
use App\Models\Event;
use App\Models\MasterData\Member;
use Illuminate\Support\Str;

class WhatsAppNotificationService
{
    /**
     * Format a given phone number into international WhatsApp standard (e.g. 628...).
     */
    public static function formatPhoneNumber(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Strip all non-digits
        $cleaned = preg_replace('/\D+/', '', $phone);

        if (empty($cleaned) || strlen($cleaned) < 8) {
            return null;
        }

        // Handle standard Indonesian formats
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62'.substr($cleaned, 1);
        } elseif (str_starts_with($cleaned, '8')) {
            $cleaned = '62'.$cleaned;
        }

        return $cleaned;
    }

    /**
     * Check if member has a valid phone number for WhatsApp.
     */
    public static function isValidPhoneNumber(?string $phone): bool
    {
        return self::formatPhoneNumber($phone) !== null;
    }

    /**
     * Generate the direct https://wa.me/{phone}?text={encoded} URL.
     */
    public static function buildWhatsAppUrl(string $phone, string $message): string
    {
        $formatted = self::formatPhoneNumber($phone) ?? $phone;

        return 'https://wa.me/'.$formatted.'?text='.rawurlencode($message);
    }

    /**
     * Generate WhatsApp notification message for active iuran.
     */
    public function generateIuranMessage(Member $member, ?Invoice $invoice = null): string
    {
        $appName = config('app.name', 'MGMT CLUB');
        $memberName = $member->full_name;
        $memberCode = $member->member_code;

        if ($invoice) {
            $amountFormatted = number_format((float) $invoice->remaining_amount, 0, ',', '.');
            $due = $invoice->due_at ? (string) $invoice->due_at : '-';
            $period = $invoice->period_start ? (string) $invoice->period_start : '-';

            return "Halo Bapak/Ibu {$memberName} ({$memberCode}),\n\n"
                ."Kami dari pengurus {$appName} ingin menginformasikan tagihan iuran Anda yang masih aktif:\n\n"
                ."📌 *No. Invoice*: {$invoice->invoice_number}\n"
                ."📅 *Periode*: {$period}\n"
                ."💰 *Sisa Tagihan*: Rp {$amountFormatted}\n"
                ."⏰ *Jatuh Tempo*: {$due}\n\n"
                ."Mohon untuk segera melakukan pembayaran dan konfirmasi melalui portal aplikasi:\n"
                .route('contrib.index')."\n\n"
                .'Terima kasih atas partisipasi dan dukungannya! 🙏';
        }

        $activeInvoices = $member->getActiveInvoices();
        $totalAmount = number_format((float) $member->getTotalActiveIuranAmount(), 0, ',', '.');
        $count = $activeInvoices->count();

        $invoiceList = '';
        $i = 1;
        foreach ($activeInvoices as $inv) {
            $amount = number_format((float) $inv->remaining_amount, 0, ',', '.');
            $period = $inv->period_start ? (string) $inv->period_start : '-';
            $due = $inv->due_at ? (string) $inv->due_at : '-';
            $invoiceList .= "{$i}. *{$inv->invoice_number}* ({$period}) - Rp {$amount} (Jatuh tempo: {$due})\n";
            $i++;
        }

        return "Halo Bapak/Ibu {$memberName} ({$memberCode}),\n\n"
            ."Kami dari pengurus {$appName} ingin menginformasikan bahwa Anda memiliki {$count} tagihan iuran aktif:\n\n"
            .trim($invoiceList)."\n\n"
            ."💰 *Total Tagihan*: Rp {$totalAmount}\n\n"
            ."Silakan lakukan pembayaran dan konfirmasi melalui tautan berikut:\n"
            .route('contrib.index')."\n\n"
            .'Terima kasih atas perhatian dan kerjasamanya! 🙏';
    }

    /**
     * Generate WhatsApp notification message for an active/upcoming event.
     */
    public function generateEventMessage(Member $member, Event $event): string
    {
        $appName = config('app.name', 'MGMT CLUB');
        $memberName = $member->full_name;
        $title = $event->post?->title ?? 'Kegiatan Club';
        $location = $event->location ?? '-';
        $period = $event->short_event_period ?? $event->event_period;
        $eventUrl = route('public.events.show', ['event' => $event->id]);

        $description = '';
        if (! empty($event->post?->description)) {
            $cleanDesc = trim(strip_tags($event->post->description));
            $description = Str::limit($cleanDesc, 160)."\n\n";
        }

        $quotaInfo = '';
        if ($event->max_attendant > 0) {
            $quotaInfo = "👥 *Kuota Peserta*: Maks {$event->max_attendant} orang\n";
        }

        return "Halo Bapak/Ibu {$memberName},\n\n"
            ."Ada kegiatan seru yang akan segera aktif/berlangsung dari {$appName}! 🎉\n\n"
            ."🏍️ *Kegiatan*: {$title}\n"
            ."📍 *Lokasi*: {$location}\n"
            ."🗓️ *Waktu*: {$period}\n"
            .$quotaInfo
            .($description ? "📝 *Keterangan*:\n{$description}" : "\n")
            ."Yuk segera daftarkan diri Anda sebelum kuota penuh melalui link berikut:\n"
            ."{$eventUrl}\n\n"
            .'Sampai jumpa di lokasi kegiatan! 🚀';
    }

    /**
     * Get the full WhatsApp URL for member active iuran.
     */
    public function getIuranNotificationUrl(Member $member, ?Invoice $invoice = null): ?string
    {
        $phone = self::formatPhoneNumber($member->phone_number);
        if (! $phone || ! $member->hasActiveIuran()) {
            return null;
        }

        $message = $this->generateIuranMessage($member, $invoice);

        return self::buildWhatsAppUrl($phone, $message);
    }

    /**
     * Get the full WhatsApp URL for an active event notification.
     */
    public function getEventNotificationUrl(Member $member, Event $event): ?string
    {
        $phone = self::formatPhoneNumber($member->phone_number);
        if (! $phone) {
            return null;
        }

        $message = $this->generateEventMessage($member, $event);

        return self::buildWhatsAppUrl($phone, $message);
    }

    /**
     * Record a reminder entry in invoice_reminders table.
     */
    public function recordInvoiceReminder(Invoice $invoice, string $reminderType = 'whatsapp', string $status = 'sent'): InvoiceReminder
    {
        return InvoiceReminder::create([
            'invoice_id' => $invoice->id,
            'reminder_type' => $reminderType,
            'scheduled_at' => now(),
            'sent_at' => now(),
            'status' => $status,
            'attempts' => 1,
        ]);
    }
}

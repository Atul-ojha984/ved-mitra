<!DOCTYPE html>
<html><head><meta charset="utf-8"></head>
<body style="font-family: 'Inter', Arial, sans-serif; background: #f9fafb; padding: 40px 0;">
<div style="max-width: 560px; margin: 0 auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06);">
    <div style="background: #1f2937; padding: 32px; text-align: center;">
        <h1 style="color: #fff; margin: 0; font-size: 24px;">Registration Update</h1>
        <p style="color: rgba(255,255,255,0.7); margin: 8px 0 0;">{{ config('app.name', 'Ved Mitra') }}</p>
    </div>
    <div style="padding: 32px;">
        <p style="color: #374151; line-height: 1.6;">Dear <strong>{{ $pandit->user->name }}</strong>,</p>
        <p style="color: #374151; line-height: 1.6;">Thank you for your interest in joining {{ config('app.name', 'Ved Mitra') }}. After careful review of your application, we regret to inform you that your registration has not been approved at this time.</p>

        @if($pandit->rejection_reason)
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px; margin: 20px 0;">
                <p style="color: #991b1b; font-weight: bold; margin: 0 0 8px;">Reason:</p>
                <p style="color: #7f1d1d; margin: 0;">{{ $pandit->rejection_reason }}</p>
            </div>
        @endif

        <p style="color: #374151; line-height: 1.6;">You may update your profile and resubmit your application for review. If you have any questions, please contact our support team.</p>
        <p style="color: #374151; margin-top: 24px;">Regards,<br><strong>{{ config('app.name', 'Ved Mitra') }} Team</strong></p>
    </div>
</div>
</body></html>

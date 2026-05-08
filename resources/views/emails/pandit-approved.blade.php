<!DOCTYPE html>
<html><head><meta charset="utf-8"></head>
<body style="font-family: 'Inter', Arial, sans-serif; background: #f9fafb; padding: 40px 0;">
<div style="max-width: 560px; margin: 0 auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06);">
    <div style="background: linear-gradient(135deg, #f97316, #eab308); padding: 32px; text-align: center;">
        <h1 style="color: #fff; margin: 0; font-size: 24px;">🎉 Congratulations!</h1>
        <p style="color: rgba(255,255,255,0.9); margin: 8px 0 0;">Your Pandit Account is Approved</p>
    </div>
    <div style="padding: 32px;">
        <p style="color: #374151; line-height: 1.6;">Dear <strong>{{ $pandit->user->name }}</strong>,</p>
        <p style="color: #374151; line-height: 1.6;">We are thrilled to inform you that your pandit registration on <strong>{{ config('app.name', 'Ved Mitra') }}</strong> has been <span style="color: #16a34a; font-weight: bold;">approved</span>!</p>
        <p style="color: #374151; line-height: 1.6;">You can now:</p>
        <ul style="color: #374151; line-height: 1.8;">
            <li>✅ Access your Pandit Dashboard</li>
            <li>✅ Receive booking requests from customers</li>
            <li>✅ Appear in public search results</li>
            <li>✅ Manage your services and availability</li>
        </ul>
        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ url('/login') }}" style="display: inline-block; background: #f97316; color: #fff; padding: 14px 32px; border-radius: 12px; text-decoration: none; font-weight: bold; font-size: 16px;">Login to Dashboard →</a>
        </div>
        <p style="color: #6b7280; font-size: 14px; line-height: 1.6;">Thank you for joining our spiritual community. We look forward to helping you serve devotees across India.</p>
        <p style="color: #374151; margin-top: 24px;">Warm Regards,<br><strong>{{ config('app.name', 'Ved Mitra') }} Team</strong></p>
    </div>
</div>
</body></html>

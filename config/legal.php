<?php

$brand = env('APP_NAME', 'Ved Mitra');

return [
    'privacy-policy' => [
        'title' => 'Privacy Policy',
        'summary' => "{$brand} protects devotee, pandit, booking, payment, and spiritual consultation information with responsible collection, storage, and usage practices.",
        'sections' => [
            ['heading' => 'Information We Collect', 'body' => 'We collect account details, contact information, booking preferences, birth details submitted for kundli generation, payment status, support messages, and platform usage signals needed to operate and improve the service.'],
            ['heading' => 'How We Use Data', 'body' => 'Data is used to create accounts, match customers with pandits, process bookings, generate kundli outputs, provide support, send service notifications, prevent misuse, and improve reliability.'],
            ['heading' => 'Payment Security', 'body' => 'Online payments are handled through payment providers. We do not store full card, UPI, or bank credentials on our servers. Payment metadata may be stored for booking reconciliation, refunds, accounting, and fraud prevention.'],
            ['heading' => 'Spiritual Information', 'body' => 'Birth details, kundli inputs, puja preferences, and consultation notes are treated as sensitive spiritual context and are used only to provide the requested experience or support.'],
            ['heading' => 'Sharing', 'body' => 'We share only the information required with assigned pandits, payment processors, hosting providers, analytics tools, legal authorities when required, and support teams bound to platform responsibilities.'],
            ['heading' => 'Your Choices', 'body' => 'Users may request profile updates, support history clarification, or account closure by contacting support. Some records may be retained where required for payments, disputes, tax, safety, or legal compliance.'],
        ],
    ],
    'terms-and-conditions' => [
        'title' => 'Terms & Conditions',
        'summary' => "These terms govern use of {$brand} for booking pandits, kundli generation, spiritual content, payments, and support interactions.",
        'sections' => [
            ['heading' => 'Platform Role', 'body' => "{$brand} is a technology platform that connects devotees with listed pandits and spiritual content. Pandits are responsible for the services, rituals, timings, and guidance they provide."],
            ['heading' => 'User Responsibilities', 'body' => 'Users agree to provide accurate booking details, respectful communication, lawful usage, and timely payment. Misuse, harassment, false claims, spam, or fraudulent activity may lead to account restrictions.'],
            ['heading' => 'Pandit Responsibilities', 'body' => 'Pandits must provide truthful profile details, maintain respectful conduct, honor accepted bookings, keep availability updated, and comply with applicable local practices and laws.'],
            ['heading' => 'Payments', 'body' => 'Prices, convenience fees, taxes, and payment terms may vary by service. A booking is confirmed only when the platform marks the payment and service status as confirmed.'],
            ['heading' => 'Content Use', 'body' => 'E-books, kundli outputs, festival information, and spiritual content are provided for learning and devotional purposes. Users may not scrape, resell, or misuse platform content without written permission.'],
            ['heading' => 'Account Action', 'body' => "{$brand} may suspend access, remove content, cancel bookings, or restrict accounts to protect users, pandits, payments, safety, compliance, or platform integrity."],
        ],
    ],
    'refund-policy' => [
        'title' => 'Refund Policy',
        'summary' => "{$brand} aims for fair refunds while respecting pandit preparation time, travel, samagri arrangements, and payment processor rules.",
        'sections' => [
            ['heading' => 'Eligible Refunds', 'body' => 'Refunds may be considered when a booking is cancelled within the allowed window, a pandit fails to attend, duplicate payment occurs, or the service cannot be fulfilled due to platform-side issues.'],
            ['heading' => 'Non-Refundable Cases', 'body' => 'Convenience fees, completed services, user no-shows, last-minute user cancellations, custom samagri purchases, travel already incurred, and materially incorrect user details may be non-refundable.'],
            ['heading' => 'Processing Time', 'body' => 'Approved refunds are initiated to the original payment method where possible. Bank and payment provider timelines may vary and are outside direct platform control.'],
            ['heading' => 'Disputes', 'body' => 'Users should contact support with booking ID, payment reference, cancellation reason, and evidence. The platform may consult the pandit and payment records before deciding.'],
        ],
    ],
    'cancellation-policy' => [
        'title' => 'Cancellation Policy',
        'summary' => 'Cancellations are handled according to service timing, pandit acceptance, travel preparation, and ritual material arrangements.',
        'sections' => [
            ['heading' => 'Before Confirmation', 'body' => 'Users may cancel pending bookings before pandit confirmation without ritual preparation charges. Payment processor charges may still apply where applicable.'],
            ['heading' => 'After Confirmation', 'body' => 'Confirmed bookings may include cancellation charges depending on how close the ceremony date is and whether travel or samagri has already been arranged.'],
            ['heading' => 'Pandit Cancellation', 'body' => 'If a pandit cancels, the platform may help find a replacement or initiate an eligible refund according to availability and user preference.'],
            ['heading' => 'Emergency Changes', 'body' => 'For urgent rescheduling, contact support as early as possible with the booking ID so the team can coordinate with the pandit.'],
        ],
    ],
    'trademark-notice' => [
        'title' => 'Trademark Notice',
        'summary' => "{$brand}, its logo text, visual identity, and platform experience are brand assets of the platform owner.",
        'sections' => [
            ['heading' => 'Brand Use', 'body' => "No person may use the {$brand} name, logo, trade dress, or confusingly similar marks for commercial services, impersonation, paid ads, or endorsements without written permission."],
            ['heading' => 'Third-Party Marks', 'body' => 'All third-party names, icons, payment marks, and social media brands belong to their respective owners and are used only for identification or integration purposes.'],
        ],
    ],
    'disclaimer' => [
        'title' => 'Disclaimer',
        'summary' => "{$brand} provides devotional, spiritual, ritual, astrological, and educational experiences. It does not replace professional legal, financial, medical, psychological, or emergency advice.",
        'sections' => [
            ['heading' => 'Spiritual Consultation Disclaimer', 'body' => 'Puja recommendations, kundli outputs, horoscope content, and pandit consultations are faith-based and interpretive. Outcomes are not guaranteed and should be used with personal judgment.'],
            ['heading' => 'No Professional Substitute', 'body' => 'For health, legal, financial, mental health, safety, or emergency matters, consult qualified professionals and local authorities.'],
            ['heading' => 'Accuracy', 'body' => 'Festival dates, tithi details, kundli calculations, and content may vary by tradition, region, panchang, and pandit interpretation. Users should verify critical ritual timing with their chosen pandit.'],
            ['heading' => 'External Media', 'body' => 'Some images, audio, and embeds may be served by third-party providers under their respective licenses and availability terms.'],
        ],
    ],
    'help-center' => [
        'title' => 'Help Center',
        'summary' => 'Find quick support for bookings, kundli generation, payments, e-books, pandit onboarding, and account access.',
        'sections' => [
            ['heading' => 'Bookings', 'body' => 'Use the booking ID shown in your dashboard when contacting support about schedule, pandit assignment, payment, cancellation, or rescheduling.'],
            ['heading' => 'Kundli', 'body' => 'For best kundli output, enter full name, exact birth date, birth time, and birth place. If results fail, check validation errors and try again.'],
            ['heading' => 'Pandit Support', 'body' => 'Pandits can update availability, review bookings, and track earnings from the pandit dashboard after approval.'],
        ],
    ],
    'faq' => [
        'title' => 'FAQ',
        'summary' => 'Common Ved Mitra questions answered clearly for devotees and pandits.',
        'sections' => [
            ['heading' => 'Can I book a pandit online?', 'body' => 'Yes. Search by city or service, review available pandits, choose a ceremony, and complete the booking flow.'],
            ['heading' => 'Is kundli generation free?', 'body' => 'The basic Ved Mitra kundli generator is available on the public kundli page. Logged-in users can save recent kundli history.'],
            ['heading' => 'Can I download e-books?', 'body' => 'Each reader page includes a PDF/print action. Your browser can save the formatted reader page as a PDF.'],
        ],
    ],
    'customer-support' => [
        'title' => 'Customer Support',
        'summary' => 'Ved Mitra support helps with bookings, account access, payments, refunds, and reader issues.',
        'sections' => [
            ['heading' => 'Support Hours', 'body' => 'Support requests are reviewed during business hours. Urgent booking concerns should include booking ID, ceremony date, and contact number.'],
            ['heading' => 'Email', 'body' => 'Write to support@vedmitra.in with your registered email and relevant booking or payment reference.'],
        ],
    ],
    'email-support' => [
        'title' => 'Email Support',
        'summary' => 'Use email support for documentation-heavy issues such as refunds, failed payments, profile verification, and account closure.',
        'sections' => [
            ['heading' => 'What To Include', 'body' => 'Include your registered email, phone number, booking ID, payment ID, screenshots if any, and a concise explanation of the issue.'],
            ['heading' => 'Response', 'body' => 'The support team reviews requests in queue order and may ask for additional verification before making payment or account changes.'],
        ],
    ],
];

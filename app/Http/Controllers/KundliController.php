<?php

namespace App\Http\Controllers;

use App\Models\Kundli;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KundliController extends Controller
{
    private const RASHIS = [
        1 => ['name' => 'Aries', 'hindi' => 'मेष', 'lord' => 'Mars', 'element' => 'Fire'],
        2 => ['name' => 'Taurus', 'hindi' => 'वृषभ', 'lord' => 'Venus', 'element' => 'Earth'],
        3 => ['name' => 'Gemini', 'hindi' => 'मिथुन', 'lord' => 'Mercury', 'element' => 'Air'],
        4 => ['name' => 'Cancer', 'hindi' => 'कर्क', 'lord' => 'Moon', 'element' => 'Water'],
        5 => ['name' => 'Leo', 'hindi' => 'सिंह', 'lord' => 'Sun', 'element' => 'Fire'],
        6 => ['name' => 'Virgo', 'hindi' => 'कन्या', 'lord' => 'Mercury', 'element' => 'Earth'],
        7 => ['name' => 'Libra', 'hindi' => 'तुला', 'lord' => 'Venus', 'element' => 'Air'],
        8 => ['name' => 'Scorpio', 'hindi' => 'वृश्चिक', 'lord' => 'Mars', 'element' => 'Water'],
        9 => ['name' => 'Sagittarius', 'hindi' => 'धनु', 'lord' => 'Jupiter', 'element' => 'Fire'],
        10 => ['name' => 'Capricorn', 'hindi' => 'मकर', 'lord' => 'Saturn', 'element' => 'Earth'],
        11 => ['name' => 'Aquarius', 'hindi' => 'कुम्भ', 'lord' => 'Saturn', 'element' => 'Air'],
        12 => ['name' => 'Pisces', 'hindi' => 'मीन', 'lord' => 'Jupiter', 'element' => 'Water'],
    ];

    private const NAKSHATRAS = [
        'Ashwini', 'Bharani', 'Krittika', 'Rohini', 'Mrigashira', 'Ardra', 'Punarvasu',
        'Pushya', 'Ashlesha', 'Magha', 'Purva Phalguni', 'Uttara Phalguni', 'Hasta',
        'Chitra', 'Swati', 'Vishakha', 'Anuradha', 'Jyeshtha', 'Moola',
        'Purva Ashadha', 'Uttara Ashadha', 'Shravana', 'Dhanishtha', 'Shatabhisha',
        'Purva Bhadrapada', 'Uttara Bhadrapada', 'Revati',
    ];

    private const LUCKY_COLORS = ['Red', 'Blue', 'Green', 'Yellow', 'White', 'Orange', 'Purple', 'Gold', 'Silver', 'Saffron', 'Pink', 'Maroon'];

    private const DOSHAS = ['Mangal Dosha', 'Kaal Sarp Dosha', 'Pitra Dosha', 'Shani Dosha', 'Nadi Dosha'];

    private const SUGGESTED_PUJAS = [
        'Ganesh Puja', 'Navgrah Shanti', 'Satyanarayan Katha', 'Rudrabhishek',
        'Laxmi Puja', 'Hanuman Puja', 'Durga Puja', 'Mahamrityunjay Jaap',
    ];

    public function showForm()
    {
        $history = auth()->check() ? auth()->user()->kundlis()->latest()->take(5)->get() : collect();

        return view('kundli.generate', compact('history'));
    }

    public function generate(Request $request)
    {
        [$validated, $kundliData, $kundli] = $this->generateKundli($request);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kundli generated successfully.',
                'kundli' => $kundliData,
                'result_url' => $kundli ? route('kundli.show', $kundli) : null,
            ]);
        }

        $history = auth()->check() ? auth()->user()->kundlis()->latest()->take(5)->get() : collect();

        return view('kundli.result', compact('kundliData', 'validated', 'kundli', 'history'));
    }

    public function apiGenerate(Request $request): JsonResponse
    {
        [$validated, $kundliData, $kundli] = $this->generateKundli($request, false);

        return response()->json([
            'success' => true,
            'message' => 'Kundli generated successfully.',
            'input' => $validated,
            'kundli' => $kundliData,
            'saved' => (bool) $kundli,
            'result_url' => $kundli ? route('kundli.show', $kundli) : null,
        ]);
    }

    public function show(Kundli $kundli)
    {
        abort_unless($kundli->user_id === auth()->id(), 403);

        $kundliData = $kundli->kundli_data;
        $validated = [
            'full_name' => $kundli->full_name,
            'dob' => $kundli->dob->toDateString(),
            'birth_time' => substr((string) $kundli->birth_time, 0, 5),
            'birth_place' => $kundli->birth_place,
        ];
        $history = auth()->user()->kundlis()->latest()->take(5)->get();

        return view('kundli.result', compact('kundliData', 'validated', 'kundli', 'history'));
    }

    public function downloadPdf(Kundli $kundli)
    {
        abort_unless($kundli->user_id === auth()->id(), 403);

        $pdf = $this->buildPdf([
            config('app.name', 'Ved Mitra').' Kundli Report',
            'Name: '.$kundli->full_name,
            'Birth Date: '.$kundli->dob->format('d M Y'),
            'Birth Time: '.substr((string) $kundli->birth_time, 0, 5),
            'Birth Place: '.$kundli->birth_place,
            'Sun Sign: '.$kundli->kundli_data['rashi']['name'],
            'Moon Sign: '.$kundli->kundli_data['moon_rashi']['name'],
            'Nakshatra: '.$kundli->kundli_data['nakshatra'],
            'Ascendant: '.$kundli->kundli_data['ascendant']['name'],
            'Doshas: '.implode(', ', $kundli->kundli_data['doshas']),
            'Lucky Numbers: '.implode(', ', $kundli->kundli_data['lucky_numbers']),
            'Lucky Colors: '.implode(', ', $kundli->kundli_data['lucky_colors']),
            'Suggested Pujas: '.implode(', ', $kundli->kundli_data['suggested_pujas']),
            'Summary: '.$kundli->kundli_data['horoscope_summary'],
            'Career: '.$kundli->kundli_data['career'],
            'Health: '.$kundli->kundli_data['health'],
            'Disclaimer: Spiritual guidance is faith-based and interpretive.',
        ]);

        $filename = Str::slug($kundli->full_name.' ved mitra kundli').'.pdf';

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    private function generateKundli(Request $request, bool $saveWhenAuthenticated = true): array
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'birth_time' => 'required|date_format:H:i',
            'birth_place' => 'required|string|max:255',
        ]);

        $kundliData = $this->calculateKundliData($validated);
        $kundli = null;

        if ($saveWhenAuthenticated && auth()->check()) {
            $kundli = Kundli::create([
                'user_id' => auth()->id(),
                'full_name' => $validated['full_name'],
                'dob' => $validated['dob'],
                'birth_time' => $validated['birth_time'],
                'birth_place' => $validated['birth_place'],
                'kundli_data' => $kundliData,
            ]);
        }

        return [$validated, $kundliData, $kundli];
    }

    private function calculateKundliData(array $validated): array
    {
        $dob = Carbon::parse($validated['dob']);

        $sunSign = (($dob->month + $dob->day) % 12) + 1;
        $moonSign = (($dob->day + $dob->year % 12) % 12) + 1;
        $nakIndex = ($dob->day + $dob->month + ($dob->year % 27)) % 27;
        $ascendant = ((int) substr($validated['birth_time'], 0, 2) % 12) + 1;

        $doshaCount = ($dob->day + $dob->month) % 3;
        $doshas = array_slice(self::DOSHAS, 0, $doshaCount + 1);

        return [
            'rashi' => self::RASHIS[$sunSign],
            'moon_rashi' => self::RASHIS[$moonSign],
            'nakshatra' => self::NAKSHATRAS[$nakIndex],
            'ascendant' => self::RASHIS[$ascendant],
            'doshas' => $doshas,
            'has_mangal_dosha' => in_array('Mangal Dosha', $doshas, true),
            'lucky_colors' => [self::LUCKY_COLORS[$sunSign - 1], self::LUCKY_COLORS[($moonSign + 3) % 12]],
            'lucky_numbers' => [($dob->day % 9) + 1, ($dob->month + $dob->day) % 9 + 1, ($dob->year % 9) + 1],
            'suggested_pujas' => array_slice(self::SUGGESTED_PUJAS, $doshaCount, 3),
            'horoscope_summary' => $this->generateSummary($sunSign, $moonSign, $doshas),
            'career' => $this->getCareerAdvice($sunSign),
            'health' => $this->getHealthAdvice($moonSign),
        ];
    }

    private function generateSummary(int $sun, int $moon, array $doshas): string
    {
        $rashi = self::RASHIS[$sun]['name'];
        $moonRashi = self::RASHIS[$moon]['name'];
        $summaries = [
            "As a {$rashi} native with a {$moonRashi} moon influence, you carry strong resolve, emotional depth, and a desire to create stability around you.",
            "Your {$rashi} sun sign indicates creativity, wisdom, and spiritual inclination, while {$moonRashi} strengthens your inner intuition.",
            "Born under {$rashi}, you are blessed with intelligence, devotion, and a caring nature that becomes stronger through disciplined spiritual practice.",
        ];
        $summary = $summaries[$sun % 3];

        if (count($doshas) > 0) {
            $summary .= ' Performing '.implode(' and ', array_slice($doshas, 0, 2)).' remedies is recommended for enhanced prosperity and peace.';
        }

        return $summary;
    }

    private function getCareerAdvice(int $sign): string
    {
        $advice = ['Leadership roles', 'Finance and banking', 'Media and communication', 'Education and nurturing', 'Entertainment', 'Healthcare', 'Law and justice', 'Research', 'Travel and exports', 'Government service', 'Technology', 'Creative arts'];

        return 'Best suited for: '.$advice[($sign - 1) % 12].'. Focus on skill development, ethical choices, and maintaining work-life balance.';
    }

    private function getHealthAdvice(int $sign): string
    {
        $advice = ['head and brain', 'throat and neck', 'lungs and arms', 'chest and stomach', 'heart and spine', 'digestive system', 'kidneys and lower back', 'reproductive system', 'thighs and liver', 'bones and joints', 'circulatory system', 'feet and immune system'];

        return 'Pay attention to '.$advice[($sign - 1) % 12].'. Regular yoga, pranayama, hydration, and meditation are recommended.';
    }

    private function buildPdf(array $lines): string
    {
        $stream = '';
        $y = 780;
        foreach ($lines as $index => $line) {
            $size = $index === 0 ? 18 : 11;
            $stream .= "BT /F1 {$size} Tf 54 {$y} Td (".$this->pdfText($line).") Tj ET\n";
            $y -= $index === 0 ? 30 : 22;
            if ($y < 54) {
                break;
            }
        }

        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 842] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>',
            '<< /Length '.strlen($stream)." >>\nstream\n".$stream."endstream",
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $number => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($number + 1)." 0 obj\n".$object."\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT)." 00000 n \n";
        }
        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }

    private function pdfText(string $text): string
    {
        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = $ascii ?: preg_replace('/[^\x20-\x7E]/', '', $text);

        return str_replace(['\\', '(', ')'], ['\\\\', '\(', '\)'], $text);
    }
}

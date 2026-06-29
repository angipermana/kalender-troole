<?php
header('Content-Type: application/json');

// Mengambil input dari JSON
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

$nama = isset($input['nama']) ? $input['nama'] : '';
$email = isset($input['email']) ? $input['email'] : '';
$phone = isset($input['phone']) ? $input['phone'] : '';
$url = isset($input['url']) ? $input['url'] : '';
$csNumber = isset($input['csNumber']) ? $input['csNumber'] : '';

// Mapping nomor ke Nama CS
$csMapping = [
  '6285100757560' => 'Nita',
  '6287825759757' => 'Dani',
  '6281223571901' => 'Widya',
  '6281318134924' => 'Jana'
];
$namaCS = isset($csMapping[$csNumber]) ? $csMapping[$csNumber] : ($csNumber ? $csNumber : 'Unknown CS');

$NOTION_TOKEN = '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';
$DATABASE_ID = '98651ff5f0724ec4b07b3a741b99a10d';

date_default_timezone_set('Asia/Jakarta');
$tanggalMasuk = date('d/m/Y H.i.s');

$data = [
    'parent' => ['database_id' => $DATABASE_ID],
    'properties' => [
        'Tanggal Masuk' => [
            'title' => [['text' => ['content' => $tanggalMasuk]]]
        ],
        'Nama' => [
            'rich_text' => [['text' => ['content' => $nama ? $nama : 'Tanpa Nama']]]
        ],
        'Email' => [
            'email' => $email ? $email : null
        ],
        'No. WhatsApp' => [
            'phone_number' => $phone ? $phone : null
        ],
        'Nama CS' => [
            'select' => ['name' => $namaCS]
        ],
        'Sumber Trafik' => [
            'url' => $url ? $url : null
        ]
    ],
    'children' => [
        [
            'object' => 'block',
            'type' => 'paragraph',
            'paragraph' => [
                'rich_text' => [
                    [
                        'text' => [
                            'content' => "Nama Lead: " . ($nama ? $nama : 'Tanpa Nama') . "\nEmail: " . ($email ? $email : '-') . "\nNo. WA: " . ($phone ? $phone : '-') . "\nURL Trafik: " . ($url ? $url : '-') . "\nDiterima Oleh: " . $namaCS
                        ]
                    ]
                ]
            ]
        ]
    ]
];

$ch = curl_init('https://api.notion.com/v1/pages');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $NOTION_TOKEN,
    'Content-Type: application/json',
    'Notion-Version: 2022-06-28'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode >= 200 && $httpcode < 300) {
    echo json_encode(['success' => true, 'data' => json_decode($response, true)]);
} else {
    http_response_code($httpcode);
    echo json_encode(['success' => false, 'error' => json_decode($response, true)]);
}
?>

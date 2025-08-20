<?php

return [
    // General
    'Language' => 'Bahasa',
    'English' => 'Inggeris',
    '中文' => 'Cina',
    'Bahasa' => 'Bahasa Melayu',
    'app.name_extended' => 'HOT TV+',

    // Sidebar Main
    'sidebar.dashboard' => 'Papan Pemuka',
    'sidebar.license_code_management.title' => 'Pengurusan Kod Lesen',
    'sidebar.license_code_management.generate' => 'Jana Kod Lesen',
    'sidebar.license_code_management.list' => 'Senarai Kod',
    'sidebar.trial_code_management.title' => 'Pengurusan Kod Percubaan',
    'sidebar.trial_code_management.generate' => 'Jana Kod Percubaan',
    'sidebar.trial_code_management.list' => 'Senarai Kod Percubaan',
    'sidebar.agent_management.title' => 'Pengurusan Ejen',
    'sidebar.agent_management.add_new' => 'Tambah Ejen Baru',
    'sidebar.agent_management.list' => 'Senarai Ejen',
    'sidebar.hotcoin_transaction' => 'Transaksi HotCoin',
    'sidebar.all_agents' => 'Semua Ejen',

    // Sidebar Secondary/User Menu
    'sidebar.help.title' => 'Bantuan',
    'sidebar.help.documentation' => 'Dokumentasi',
    'sidebar.help.support' => 'Sokongan',
    'sidebar.settings.title' => 'Tetapan',
    'sidebar.settings.account' => 'Tetapan Akaun',
    'sidebar.settings.system' => 'Tetapan Sistem',
    'sidebar.notifications' => 'Pemberitahuan',
    'sidebar.change_password' => 'Tukar Kata Laluan',
    'sidebar.profile' => 'Profil',
    'sidebar.costing_management' => 'Pengurusan Kos',
    'sidebar.my_profile' => 'Profil Saya',
    'sidebar.delete_account' => 'Padam Akaun',
    'sidebar.dark_mode' => 'Mod Gelap',
    'sidebar.log_out' => 'Log Keluar',
    'my_profile' => 'Profil Saya',
    'language' => 'Bahasa',
    'dark_mode' => 'Mod Gelap',
    'log_out' => 'Log keluar',
    'profile' => [
        'update_profile_information' => [
            'title' => 'Maklumat Profil',
            'description' => 'Kemas kini maklumat profil dan alamat e-mel akaun anda.',
        ],
        'update_password' => [
            'title' => 'Kemas kini Kata Laluan',
            'description' => 'Pastikan akaun anda menggunakan kata laluan yang panjang dan rawak untuk kekal selamat.',
        ],
        'delete_account' => [
            'title' => 'Padam Akaun',
            'delete_account_warning' => 'Sebaik sahaja akaun anda dipadamkan, semua sumber dan datanya akan dipadamkan secara kekal. Sebelum memadamkan akaun anda, sila muat turun sebarang data atau maklumat yang ingin anda simpan.',
            'button' => 'Padam Akaun',
            'modal_title' => 'Adakah anda pasti mahu memadamkan akaun anda?',
            'modal_description' => 'Setelah akaun anda dipadamkan, semua sumber dan datanya akan dipadamkan secara kekal. Sila masukkan kata laluan anda untuk mengesahkan anda ingin memadamkan akaun anda secara kekal.',
        ],
        'form' => [
            'name' => 'Nama',
            'email' => 'E-mel',
            'current_password' => 'Kata Laluan Semasa',
            'new_password' => 'Kata Laluan Baharu',
            'confirm_password' => 'Sahkan Kata Laluan',
            'password' => 'Kata Laluan',
            'save' => 'Simpan',
            'cancel' => 'Batal',
        ],
        'unverified_email' => 'Alamat e-mel anda belum disahkan.',
        'resend_verification' => 'Klik di sini untuk menghantar semula e-mel pengesahan.',
        'verification_link_sent' => 'Pautan pengesahan baharu telah dihantar ke alamat e-mel anda.',
        'saved' => 'Disimpan.',
    ],

    'trial_code_list' => [
        'title' => 'Senarai Kod Percubaan',
        'trial_code_id' => 'ID Kod Percubaan',
        'select_status' => 'Pilih Status',
        'search' => 'Cari',
        'export_excel' => 'Eksport Excel',
        'access_record' => 'Rekod Akses',
        'batch_generation' => 'Penjanaan Kod Percubaan Berkelompok',
        'quantity_available' => 'Kuantiti kod percubaan tersedia',
        'table' => [
            'id' => 'ID',
            'trial_code_id' => 'ID Kod Percubaan',
            'status' => 'Status',
            'remarks' => 'Catatan',
            'expired_date' => 'Tarikh Luput',
            'created_time' => 'Masa Dicipta',
            'action' => 'Tindakan',
            'update_remarks' => 'Kemas kini Catatan',
        ],
    ],

    'activation_code_list' => [
        'title' => 'Senarai Kod Pengaktifan',
        'batch_generation' => 'JANA KOD PENGAKTIFAN BERKELOMPOK',
        'activation_code_id' => 'ID Kod Pengaktifan',
        'select_status' => 'Pilih Status',
        'select_activation_type' => 'Sila pilih Jenis Pengaktifan',
        'select_date_range' => 'Sila pilih julat tarikh',
        'search' => 'CARI',
        'export_excel' => 'EKSPORT EXCEL',
        'table' => [
            'id' => 'ID',
            'activation_code_id' => 'ID KOD PENGAKTIFAN',
            'type' => 'JENIS',
            'status' => 'STATUS',
            'remarks' => 'CATATAN',
            'expired_date' => 'TARIKH LUPUT',
            'created_time' => 'MASA DICIPTA',
            'action' => 'TINDAKAN',
        ],
    ],

    'license_list' => [
        'title' => 'Senarai Kod Lesen',
        'showing_records' => 'Menunjukkan :count daripada :total kod lesen',
        'search_placeholder' => 'Cari kod',
        'table' => [
            'code' => 'Kod',
            'type' => 'Jenis',
            'agent' => 'Ejen',
            'status' => 'Status',
            'created_at' => 'Dicipta Pada',
            'actions' => 'Tindakan',
        ],
    ],

    'agent_list' => [
        'title' => 'Senarai Ejen',
        'add_new_agent' => 'Tambah Ejen Baharu',
        'agent_name_placeholder' => 'Nama Ejen',
        'search' => 'Cari',
        'table' => [
            'id' => 'ID',
            'agent_name' => 'Nama Ejen',
            'agent_level' => 'Tahap Ejen',
            'balance' => 'Baki',
            'accumulated_profit' => 'Keuntungan Terkumpul',
            'remark' => 'Catatan',
            'created_time' => 'Masa Dicipta',
            'action' => 'Tindakan',
        ],
    ],

    'agent_create' => [
        'title' => 'Tambah Ejen Baharu',
        'top_up_amount' => 'Jumlah Tambah Nilai',
        'available_hotcoin' => 'Jumlah HOTCOIN Anda yang Tersedia',
        'permission' => 'Kebenaran',
        'permission_normal' => 'Biasa',
        'permission_enhanced' => 'Dipertingkat',
        'reset' => 'Set Semula',
        'return' => 'Kembali',
        'select_level' => 'Pilih',
    ],

    // License Generation Page
    'license_generate' => [
        'title' => 'Penjanaan Kod Pengaktifan Secara Berkelompok',
        'type' => 'Jenis',
        'choose_code_type' => 'Sila Pilih Jenis Kod',
        'quantity' => 'Kuantiti',
        'remarks' => 'Catatan',
        'need_hotcoin' => 'HOTCOIN Diperlukan',
        'hotcoin_balance' => 'Baki HOTCOIN',
        'batch_generate' => 'JANA KOD PENGAKTIFAN SECARA BERKELOMPOK',
        'reset' => 'TETAPKAN SEMULA',
        'return' => 'KEMBALI',
        'license_30_day' => 'Kod lesen 30 hari 7.50 HOTCOIN',
        'license_90_day' => 'Kod lesen 90 hari 15.00 HOTCOIN',
        'license_180_day' => 'Kod lesen 180 hari 30.00 HOTCOIN',
        'license_365_day' => 'Kod lesen 365 hari 60.00 HOTCOIN',
        'license_1_day' => 'Kod lesen 1 hari 1.00 HOTCOIN',
        'license_7_day' => 'Kod lesen 7 hari 3.00 HOTCOIN',
    ],

    // Trial Generation Page
    'trial_generate' => [
        'title' => 'Mohon Kod Percubaan',
        'available_quantity' => 'Kuantiti tersedia',
        'generation_quantity' => 'Kuantiti penjanaan',
        'remarks' => 'Catatan',
        'apply_for_trial_code' => 'MOHON KOD PERCUBAAN',
        'reset' => 'TETAPKAN SEMULA',
        'return' => 'KEMBALI',
    ],

    // Costing Page
    'costing' => [
        'title' => 'Senarai Konfigurasi Tahap',
        'table' => [
            'license_code_type' => 'JENIS KOD LESEN',
            'retail_price' => 'HARGA RUNCIT',
            'your_cost' => 'KOS ANDA',
            'diamond_agent_cost' => 'KOS EJEN BERLIAN',
            'gold_agent_cost' => 'KOS EJEN EMAS',
            'silver_agent_cost' => 'KOS EJEN PERAK',
            'bronze_agent_cost' => 'KOS EJEN GANGSA',
            'customized_minimum_cost' => 'KOS MINIMUM TERSUAI',
            'action' => 'TINDAKAN',
        ],
    ],

    // General translations
    'general' => [
        'select_code' => 'Pilih Kod',
        'authorization_code' => 'Kod Pengesahan',
        'message' => 'Mesej',
        'membership_authorization_code' => 'Kod Pengesahan Keahlian',
        'confirm' => 'Sahkan',
        'select' => 'Pilih',
        'createSuccess' => 'Berjaya dicipta',
        'createFailed' => 'Gagal dicipta',
        'updateSuccess' => 'Berjaya dikemas kini',
        'updateFailed' => 'Gagal dikemas kini',
        'generate' => 'dijana',
        'return' => 'Kembali',
        'error' => 'Ralat',
    ],

    // Home translations
    'home' => [
        'copy' => 'Salin',
    ],

    // AuthCode translations
    'authCode' => [
        'remark' => 'Catatan',
        'auth_code_fail' => 'Penjanaan kod pengesahan gagal',
        'exceed' => 'Melebihi had',
    ],

    // Huobi translations
    'huobi' => [
        'money' => 'USD',
    ],
];

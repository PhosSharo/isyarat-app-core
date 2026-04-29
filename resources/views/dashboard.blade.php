<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>isyarat-app-core &mdash; Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #FFFDF7;
            --fg: #1a1a1a;
            --border: #1a1a1a;
            --border-w: 3px;
            --shadow: 5px 5px 0 #1a1a1a;
            --shadow-sm: 3px 3px 0 #1a1a1a;
            --radius: 0;

            /* Neo-Brutalism accent palette */
            --yellow: #FFE156;
            --pink: #FF6B9D;
            --blue: #7EB6FF;
            --green: #A8E6A3;
            --orange: #FFB067;
            --purple: #C4A1FF;
            --red: #FF6B6B;
            --cyan: #6FEDD6;

            --font-display: 'Syne', sans-serif;
            --font-mono: 'Space Mono', monospace;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-mono);
            background: var(--bg);
            color: var(--fg);
            padding: 28px;
            max-width: 1440px;
            margin: 0 auto;
            font-size: 13px;
            line-height: 1.5;
        }

        /* ── Header ── */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: var(--border-w) solid var(--border);
            background: var(--yellow);
            padding: 16px 24px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }
        .header h1 {
            font-family: var(--font-display);
            font-size: 26px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header .tag {
            font-family: var(--font-mono);
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border: 2px solid var(--border);
            background: #fff;
        }

        /* ── Main tabs ── */
        .main-tabs { display: flex; gap: 0; margin-bottom: 0; }
        .main-tab {
            padding: 12px 28px;
            border: var(--border-w) solid var(--border);
            border-bottom: none;
            background: #fff;
            color: var(--fg);
            font-family: var(--font-display);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: transform 0.1s;
        }
        .main-tab:hover:not(.active) { background: var(--yellow); }
        .main-tab.active {
            background: var(--fg);
            color: #fff;
            position: relative;
            z-index: 2;
        }
        .main-panel {
            display: none;
            border: var(--border-w) solid var(--border);
            border-top: none;
            padding: 24px;
            background: #fff;
            box-shadow: var(--shadow);
        }
        .main-panel.active { display: block; }

        /* ── Sub tabs (data tables) ── */
        .sub-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 20px;
        }
        .sub-tab {
            padding: 6px 14px;
            border: 2px solid var(--border);
            background: #fff;
            color: var(--fg);
            font-family: var(--font-mono);
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-sm);
            transition: all 0.1s;
        }
        .sub-tab:hover:not(.active) {
            transform: translate(1px, 1px);
            box-shadow: 2px 2px 0 var(--border);
        }
        .sub-tab.active {
            background: var(--fg);
            color: #fff;
            transform: translate(3px, 3px);
            box-shadow: none;
        }
        .sub-panel { display: none; }
        .sub-panel.active { display: block; }

        /* ── Stats cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }
        .stat-card {
            border: var(--border-w) solid var(--border);
            padding: 16px;
            box-shadow: var(--shadow-sm);
            transition: transform 0.1s, box-shadow 0.1s;
        }
        .stat-card:hover {
            transform: translate(-2px, -2px);
            box-shadow: 7px 7px 0 var(--border);
        }
        .stat-card:nth-child(9n+1) { background: var(--yellow); }
        .stat-card:nth-child(9n+2) { background: var(--pink); }
        .stat-card:nth-child(9n+3) { background: var(--blue); }
        .stat-card:nth-child(9n+4) { background: var(--green); }
        .stat-card:nth-child(9n+5) { background: var(--orange); }
        .stat-card:nth-child(9n+6) { background: var(--purple); }
        .stat-card:nth-child(9n+7) { background: var(--cyan); }
        .stat-card:nth-child(9n+8) { background: #FFD6E0; }
        .stat-card:nth-child(9n+9) { background: #D4F0FF; }
        .stat-card h3 {
            font-family: var(--font-display);
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border);
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .stat-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 3px;
        }
        .stat-row .label { opacity: 0.7; }
        .stat-row .value { font-weight: 700; }
        .stat-card .unit {
            font-size: 10px;
            opacity: 0.6;
            margin-top: 8px;
            text-align: right;
            font-style: italic;
        }

        /* ── Section ── */
        .section { margin-bottom: 28px; }
        .section h2 {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 14px;
            background: var(--yellow);
            border: 2px solid var(--border);
            display: inline-block;
            box-shadow: var(--shadow-sm);
            margin-bottom: 12px;
        }
        .section-meta {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
            padding-left: 2px;
        }

        /* ── Tables ── */
        .table-wrap {
            overflow-x: auto;
            border: var(--border-w) solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        thead { background: var(--fg); color: #fff; }
        th {
            padding: 8px 12px;
            text-align: left;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            white-space: nowrap;
            font-family: var(--font-display);
        }
        td {
            padding: 6px 12px;
            border-bottom: 1px solid #e0e0e0;
            white-space: nowrap;
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        tr:nth-child(even) td { background: #FAFAF5; }
        tr:hover td { background: var(--yellow) !important; }
        .empty-msg {
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #999;
            border: 2px dashed var(--border);
            background: #FAFAF5;
        }

        /* ── Pagination ── */
        .pag {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            font-size: 11px;
        }
        .pag a, .pag span.cur {
            padding: 4px 12px;
            border: 2px solid var(--border);
            text-decoration: none;
            color: var(--fg);
            font-family: var(--font-mono);
            font-size: 11px;
            font-weight: 700;
            box-shadow: 2px 2px 0 var(--border);
            transition: all 0.1s;
        }
        .pag a:hover {
            background: var(--yellow);
            transform: translate(1px, 1px);
            box-shadow: 1px 1px 0 var(--border);
        }
        .pag span.cur {
            background: var(--fg);
            color: #fff;
            box-shadow: none;
            transform: translate(2px, 2px);
        }
        .pag .dis { opacity: 0.3; pointer-events: none; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border: 2px solid var(--border);
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
        }
        .badge-active { background: var(--green); }
        .bool-t { font-weight: 700; color: #1a7a1a; }
        .bool-f { color: #999; }

        /* ── Endpoint reference ── */
        .ep-group {
            font-family: var(--font-display);
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 28px;
            margin-bottom: 10px;
            padding: 8px 14px;
            border: 2px solid var(--border);
            background: var(--cyan);
            display: inline-block;
            box-shadow: var(--shadow-sm);
        }
        .ep-card {
            border: var(--border-w) solid var(--border);
            margin-bottom: 12px;
            box-shadow: var(--shadow-sm);
            background: #fff;
        }
        .ep-head {
            padding: 8px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 2px solid #e0e0e0;
            background: #FAFAF5;
        }
        .ep-head code {
            font-size: 13px;
            font-weight: 700;
            font-family: var(--font-mono);
        }
        .method {
            display: inline-block;
            padding: 3px 10px;
            font-size: 10px;
            font-weight: 700;
            font-family: var(--font-display);
            text-transform: uppercase;
            border: 2px solid var(--border);
            min-width: 56px;
            text-align: center;
        }
        .method.get { background: var(--blue); color: var(--fg); }
        .method.post { background: var(--green); color: var(--fg); }
        .method.put { background: var(--orange); color: var(--fg); }
        .method.delete { background: var(--red); color: #fff; }
        .ep-body, .ep-desc { padding: 8px 14px; font-size: 12px; }
        .ep-desc { color: #555; border-bottom: 1px solid #eee; }
        .ep-json {
            margin: 0;
            padding: 14px;
            background: var(--fg);
            color: var(--green);
            border-top: 2px solid var(--border);
            border-bottom: 2px solid var(--border);
            font-size: 11px;
            line-height: 1.6;
            overflow-x: auto;
            white-space: pre;
            font-family: var(--font-mono);
        }
        .ep-fields { width: 100%; border-collapse: collapse; font-size: 11px; }
        .ep-fields th {
            padding: 6px 12px;
            background: var(--yellow);
            color: var(--fg);
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            font-family: var(--font-display);
            border: 1px solid var(--border);
        }
        .ep-fields td {
            padding: 5px 12px;
            border: 1px solid #e0e0e0;
        }
        .ep-note {
            padding: 6px 14px;
            font-size: 10px;
            color: #888;
            font-style: italic;
            background: #FAFAF5;
            border-top: 1px solid #eee;
        }
        .ep-order { margin: 8px 0 0 20px; font-size: 12px; line-height: 2; }
        .ep-order li { margin-bottom: 2px; }
        .ep-order strong { background: var(--yellow); padding: 1px 4px; border: 1px solid var(--border); }

        /* ── Auth badge ── */
        .auth-badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            border: 2px solid var(--border);
            margin-left: 8px;
            letter-spacing: 0.5px;
        }
        .auth-badge.public { background: var(--green); }
        .auth-badge.protected { background: var(--pink); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            body { padding: 12px; }
            .header { flex-direction: column; gap: 8px; text-align: center; }
            .header h1 { font-size: 20px; }
            .main-tab { padding: 10px 16px; font-size: 12px; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>isyarat-app-core &mdash; Dashboard</h1>
    <span class="tag">Laravel 12 + Sanctum</span>
</div>

{{-- ================================================================ --}}
{{-- MAIN TABS: Data | API Reference                                  --}}
{{-- ================================================================ --}}
<div class="main-tabs">
    <button class="main-tab active" onclick="mainTab('data')">Data</button>
    <button class="main-tab" onclick="mainTab('endpoints')">API Reference</button>
</div>

{{-- ================================================================ --}}
{{-- PANEL: DATA                                                      --}}
{{-- ================================================================ --}}
<div class="main-panel active" id="panel-data">

    {{-- Overview stats --}}
    <div class="stats-grid">
        @foreach ($stats as $stat)
        <div class="stat-card">
            <h3>{{ $stat['title'] }}</h3>
            @foreach ($stat['rows'] as $label => $value)
            <div class="stat-row">
                <span class="label">{{ $label }}</span>
                <span class="value">{{ $value }}</span>
            </div>
            @endforeach
            <div class="unit">Satuan: {{ $stat['satuan'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Sub tabs for 9 data tables --}}
    <div class="sub-tabs">
        <button class="sub-tab active" onclick="subTab('gestures')">1. Gestur</button>
        <button class="sub-tab" onclick="subTab('users')">2. Pengguna</button>
        <button class="sub-tab" onclick="subTab('vocabularies')">3. Kosakata</button>
        <button class="sub-tab" onclick="subTab('translations')">4. Terjemahan</button>
        <button class="sub-tab" onclick="subTab('audio')">5. Audio</button>
        <button class="sub-tab" onclick="subTab('categories')">6. Kategori</button>
        <button class="sub-tab" onclick="subTab('history')">7. Riwayat</button>
        <button class="sub-tab" onclick="subTab('models')">8. Model AI</button>
        <button class="sub-tab" onclick="subTab('feedbacks')">9. Feedback</button>
    </div>

    {{-- 1. Gestur Tangan --}}
    <div class="sub-panel active" id="sub-gestures">
        <div class="section">
            <h2>1. Data Gestur Tangan</h2>
            <div class="section-meta">Satuan: Jumlah gestur (buah/item)</div>
            @if ($gestures->isEmpty())
                <div class="empty-msg">Tidak ada data gestur.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>ID</th><th>Vocabulary</th><th>Hand</th><th>Type</th><th>Frames</th><th>Confidence</th><th>Dataset</th><th>Contributor</th><th>Created</th></tr></thead>
                        <tbody>
                        @foreach ($gestures as $g)
                            <tr>
                                <td>{{ $g->id }}</td>
                                <td>{{ $g->vocabulary->word ?? $g->vocabulary_id }}</td>
                                <td>{{ $g->hand }}</td>
                                <td>{{ $g->gesture_type }}</td>
                                <td>{{ $g->frame_count }}</td>
                                <td>{{ $g->confidence_score !== null ? number_format($g->confidence_score, 3) : '-' }}</td>
                                <td>{{ $g->source_dataset ?? '-' }}</td>
                                <td>{{ $g->contributor_id ?? '-' }}</td>
                                <td>{{ $g->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pag"><span>{{ $gestures->count() }} total</span></div>
            @endif
        </div>
    </div>

    {{-- 2. Pengguna --}}
    <div class="sub-panel" id="sub-users">
        <div class="section">
            <h2>2. Data Pengguna</h2>
            <div class="section-meta">Satuan: Jumlah pengguna (orang)</div>
            @if ($users->isEmpty())
                <div class="empty-msg">Tidak ada data pengguna.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Language</th><th>Active</th><th>Histories</th><th>Feedbacks</th><th>Created</th></tr></thead>
                        <tbody>
                        @foreach ($users as $u)
                            <tr>
                                <td>{{ $u->id }}</td>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->role }}</td>
                                <td>{{ $u->preferred_language }}</td>
                                <td>{!! $u->is_active ? '<span class="bool-t">Yes</span>' : '<span class="bool-f">No</span>' !!}</td>
                                <td>{{ $u->translation_histories_count }}</td>
                                <td>{{ $u->feedbacks_count }}</td>
                                <td>{{ $u->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pag"><span>{{ $users->count() }} total</span></div>
            @endif
        </div>
    </div>

    {{-- 3. Kosakata --}}
    <div class="sub-panel" id="sub-vocabularies">
        <div class="section">
            <h2>3. Data Kosakata Bahasa Isyarat</h2>
            <div class="section-meta">Satuan: Jumlah kata (kata/item)</div>
            @if ($vocabularies->isEmpty())
                <div class="empty-msg">Tidak ada data kosakata.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>ID</th><th>Word</th><th>Language</th><th>Type</th><th>Category</th><th>Words</th><th>Chars</th><th>Gestures</th><th>Active</th><th>Created</th></tr></thead>
                        <tbody>
                        @foreach ($vocabularies as $v)
                            <tr>
                                <td>{{ $v->id }}</td>
                                <td>{{ $v->word }}</td>
                                <td>{{ $v->language }}</td>
                                <td>{{ $v->type }}</td>
                                <td>{{ $v->category->name ?? $v->category_id }}</td>
                                <td>{{ $v->word_count }}</td>
                                <td>{{ $v->character_count }}</td>
                                <td>{{ $v->gestures_count }}</td>
                                <td>{!! $v->is_active ? '<span class="bool-t">Yes</span>' : '<span class="bool-f">No</span>' !!}</td>
                                <td>{{ $v->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pag"><span>{{ $vocabularies->count() }} total</span></div>
            @endif
        </div>
    </div>

    {{-- 4. Terjemahan --}}
    <div class="sub-panel" id="sub-translations">
        <div class="section">
            <h2>4. Data Terjemahan Teks</h2>
            <div class="section-meta">Satuan: Jumlah karakter / kata (string)</div>
            @if ($translations->isEmpty())
                <div class="empty-msg">Tidak ada data terjemahan.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>ID</th><th>Vocabulary</th><th>Src Lang</th><th>Tgt Lang</th><th>Source Text</th><th>Translated</th><th>Chars</th><th>Created</th></tr></thead>
                        <tbody>
                        @foreach ($translations as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->vocabulary->word ?? $t->vocabulary_id }}</td>
                                <td>{{ $t->source_language }}</td>
                                <td>{{ $t->target_language }}</td>
                                <td title="{{ $t->source_text }}">{{ Str::limit($t->source_text, 50) }}</td>
                                <td title="{{ $t->translated_text }}">{{ Str::limit($t->translated_text, 50) }}</td>
                                <td>{{ $t->character_count }}</td>
                                <td>{{ $t->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pag"><span>{{ $translations->count() }} total</span></div>
            @endif
        </div>
    </div>

    {{-- 5. Audio --}}
    <div class="sub-panel" id="sub-audio">
        <div class="section">
            <h2>5. Data Audio/Suara</h2>
            <div class="section-meta">Satuan: Durasi (detik) / Ukuran file (MB)</div>
            @if ($audioFiles->isEmpty())
                <div class="empty-msg">Tidak ada data audio.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>ID</th><th>Vocabulary</th><th>File</th><th>MIME</th><th>Duration (s)</th><th>Size (MB)</th><th>Lang</th><th>Type</th><th>Transcript</th><th>Created</th></tr></thead>
                        <tbody>
                        @foreach ($audioFiles as $a)
                            <tr>
                                <td>{{ $a->id }}</td>
                                <td>{{ $a->vocabulary->word ?? $a->vocabulary_id ?? '-' }}</td>
                                <td title="{{ $a->file_path }}">{{ Str::limit($a->file_path, 40) }}</td>
                                <td>{{ $a->mime_type }}</td>
                                <td>{{ $a->duration_seconds !== null ? number_format($a->duration_seconds, 2) : '-' }}</td>
                                <td>{{ $a->file_size_mb !== null ? number_format($a->file_size_mb, 4) : '-' }}</td>
                                <td>{{ $a->language }}</td>
                                <td>{{ $a->type }}</td>
                                <td title="{{ $a->transcript }}">{{ Str::limit($a->transcript, 40) ?? '-' }}</td>
                                <td>{{ $a->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pag"><span>{{ $audioFiles->count() }} total</span></div>
            @endif
        </div>
    </div>

    {{-- 6. Kategori --}}
    <div class="sub-panel" id="sub-categories">
        <div class="section">
            <h2>6. Data Kategori Kosakata</h2>
            <div class="section-meta">Satuan: Jumlah kategori (kategori)</div>
            @if ($categories->isEmpty())
                <div class="empty-msg">Tidak ada data kategori.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>Description</th><th>Language</th><th>Sort</th><th>Vocabularies</th><th>Active</th><th>Created</th></tr></thead>
                        <tbody>
                        @foreach ($categories as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->name }}</td>
                                <td>{{ $c->slug }}</td>
                                <td title="{{ $c->description }}">{{ Str::limit($c->description, 50) ?? '-' }}</td>
                                <td>{{ $c->language }}</td>
                                <td>{{ $c->sort_order }}</td>
                                <td>{{ $c->vocabularies_count }}</td>
                                <td>{!! $c->is_active ? '<span class="bool-t">Yes</span>' : '<span class="bool-f">No</span>' !!}</td>
                                <td>{{ $c->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pag"><span>{{ $categories->count() }} total</span></div>
            @endif
        </div>
    </div>

    {{-- 7. Riwayat --}}
    <div class="sub-panel" id="sub-history">
        <div class="section">
            <h2>7. Data Riwayat Terjemahan</h2>
            <div class="section-meta">Satuan: Jumlah sesi (sesi/log)</div>
            @if ($histories->isEmpty())
                <div class="empty-msg">Tidak ada data riwayat.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>ID</th><th>User</th><th>Session</th><th>Direction</th><th>Input</th><th>Output</th><th>In Lang</th><th>Out Lang</th><th>Confidence</th><th>Duration</th><th>Correct</th><th>Created</th></tr></thead>
                        <tbody>
                        @foreach ($histories as $h)
                            <tr>
                                <td>{{ $h->id }}</td>
                                <td>{{ $h->user_id ?? '-' }}</td>
                                <td title="{{ $h->session_id }}">{{ Str::limit($h->session_id, 20) }}</td>
                                <td>{{ $h->direction }}</td>
                                <td title="{{ $h->input_data }}">{{ Str::limit($h->input_data, 30) ?? '-' }}</td>
                                <td title="{{ $h->output_data }}">{{ Str::limit($h->output_data, 30) ?? '-' }}</td>
                                <td>{{ $h->input_language }}</td>
                                <td>{{ $h->output_language }}</td>
                                <td>{{ $h->confidence_score !== null ? number_format($h->confidence_score, 3) : '-' }}</td>
                                <td>{{ $h->duration_seconds !== null ? number_format($h->duration_seconds, 2) : '-' }}</td>
                                <td>
                                    @if ($h->is_correct === true) <span class="bool-t">Yes</span>
                                    @elseif ($h->is_correct === false) <span class="bool-f">No</span>
                                    @else <span class="bool-f">-</span>
                                    @endif
                                </td>
                                <td>{{ $h->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pag"><span>{{ $histories->count() }} total</span></div>
            @endif
        </div>
    </div>

    {{-- 8. Model AI --}}
    <div class="sub-panel" id="sub-models">
        <div class="section">
            <h2>8. Data Model AI/ML</h2>
            <div class="section-meta">Satuan: Tingkat akurasi (%)</div>
            @if ($models->isEmpty())
                <div class="empty-msg">Tidak ada data model.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>ID</th><th>Name</th><th>Ver</th><th>Type</th><th>Lang</th><th>Accuracy</th><th>Classes</th><th>Train</th><th>Val</th><th>Size</th><th>Status</th><th>Active</th><th>Feedbacks</th><th>Created</th></tr></thead>
                        <tbody>
                        @foreach ($models as $m)
                            <tr>
                                <td>{{ $m->id }}</td>
                                <td>{{ $m->name }}</td>
                                <td>{{ $m->version }}</td>
                                <td>{{ $m->type }}</td>
                                <td>{{ $m->language }}</td>
                                <td>{{ $m->accuracy_percent !== null ? number_format($m->accuracy_percent, 2) . '%' : '-' }}</td>
                                <td>{{ $m->num_classes ?? '-' }}</td>
                                <td>{{ $m->training_samples ?? '-' }}</td>
                                <td>{{ $m->validation_samples ?? '-' }}</td>
                                <td>{{ $m->file_size_mb !== null ? number_format($m->file_size_mb, 2) : '-' }}</td>
                                <td><span class="badge {{ $m->status === 'deployed' ? 'badge-active' : '' }}">{{ $m->status }}</span></td>
                                <td>{!! $m->is_active ? '<span class="bool-t">Yes</span>' : '<span class="bool-f">No</span>' !!}</td>
                                <td>{{ $m->feedbacks_count }}</td>
                                <td>{{ $m->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pag"><span>{{ $models->count() }} total</span></div>
            @endif
        </div>
    </div>

    {{-- 9. Feedback --}}
    <div class="sub-panel" id="sub-feedbacks">
        <div class="section">
            <h2>9. Data Feedback Pengguna</h2>
            <div class="section-meta">Satuan: Jumlah respons (benar/salah)</div>
            @if ($feedbacks->isEmpty())
                <div class="empty-msg">Tidak ada data feedback.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>ID</th><th>User</th><th>History</th><th>Model</th><th>Type</th><th>Correct</th><th>Expected</th><th>Rating</th><th>Comment</th><th>Created</th></tr></thead>
                        <tbody>
                        @foreach ($feedbacks as $f)
                            <tr>
                                <td>{{ $f->id }}</td>
                                <td>{{ $f->user_id ?? '-' }}</td>
                                <td>{{ $f->translation_history_id ?? '-' }}</td>
                                <td>{{ $f->ai_model_id ?? '-' }}</td>
                                <td>{{ $f->type }}</td>
                                <td>
                                    @if ($f->is_correct === true) <span class="bool-t">Benar</span>
                                    @elseif ($f->is_correct === false) <span class="bool-f">Salah</span>
                                    @else <span class="bool-f">-</span>
                                    @endif
                                </td>
                                <td title="{{ $f->expected_output }}">{{ Str::limit($f->expected_output, 30) ?? '-' }}</td>
                                <td>{{ $f->rating !== null ? $f->rating . '/5' : '-' }}</td>
                                <td title="{{ $f->comment }}">{{ Str::limit($f->comment, 40) ?? '-' }}</td>
                                <td>{{ $f->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pag"><span>{{ $feedbacks->count() }} total</span></div>
            @endif
        </div>
    </div>

</div>{{-- /panel-data --}}

{{-- ================================================================ --}}
{{-- PANEL: API REFERENCE                                             --}}
{{-- ================================================================ --}}
<div class="main-panel" id="panel-endpoints">
<div class="section">
    <h2>API Endpoint Reference</h2>
    <div class="section-meta">
        Base URL: <strong>https://&lt;your-ngrok-id&gt;.ngrok-free.app/api</strong>
        &nbsp;|&nbsp; Header: <code>Accept: application/json</code>
        &nbsp;|&nbsp; Header: <code>ngrok-skip-browser-warning: true</code>
    </div>

    {{-- Health --}}
    <h3 class="ep-group">Health Check</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/health</code></div>
        <div class="ep-body">No body. Returns status, app name, timestamp.</div>
    </div>

    {{-- Dashboard Stats --}}
    <h3 class="ep-group">Dashboard Stats</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/dashboard/stats</code></div>
        <div class="ep-body">No body. Returns aggregate counts for all 9 data variables.</div>
    </div>

    {{-- Authentication --}}
    <h3 class="ep-group">Authentication (Sanctum)</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/register</code><span class="auth-badge public">Public</span></div>
        <div class="ep-desc">Register a new user and receive a Sanctum API token.</div>
        <pre class="ep-json">{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "secret123",
    "password_confirmation": "secret123",
    "role": "user",
    "preferred_language": "bisindo"
}</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>name</td><td>string</td><td>Yes</td><td>max 255</td></tr>
                <tr><td>email</td><td>string</td><td>Yes</td><td>unique, valid email</td></tr>
                <tr><td>password</td><td>string</td><td>Yes</td><td>min 8 chars</td></tr>
                <tr><td>password_confirmation</td><td>string</td><td>Yes</td><td>must match password</td></tr>
                <tr><td>role</td><td>enum</td><td>No</td><td><code>user</code> | <code>admin</code> | <code>researcher</code> (default: user)</td></tr>
                <tr><td>preferred_language</td><td>enum</td><td>No</td><td><code>bisindo</code> | <code>asl</code> (default: bisindo)</td></tr>
            </tbody>
        </table>
        <div class="ep-note">Returns: user object + Bearer token. Password is hashed automatically.</div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/login</code><span class="auth-badge public">Public</span></div>
        <div class="ep-desc">Authenticate and receive a Sanctum API token.</div>
        <pre class="ep-json">{
    "email": "john@example.com",
    "password": "secret123"
}</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>email</td><td>string</td><td>Yes</td><td>valid email</td></tr>
                <tr><td>password</td><td>string</td><td>Yes</td><td></td></tr>
            </tbody>
        </table>
        <div class="ep-note">Returns 401 on bad credentials, 403 if account is deactivated.</div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/logout</code><span class="auth-badge protected">Auth Required</span></div>
        <div class="ep-desc">Revoke the current access token.</div>
        <div class="ep-body">No body. Send <code>Authorization: Bearer &lt;token&gt;</code> header.</div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/user</code><span class="auth-badge protected">Auth Required</span></div>
        <div class="ep-desc">Get the authenticated user's profile.</div>
        <div class="ep-body">No body. Send <code>Authorization: Bearer &lt;token&gt;</code> header.</div>
    </div>

    {{-- 1. Gestures --}}
    <h3 class="ep-group">1. Data Gestur Tangan &mdash; Jumlah gestur (buah/item)</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/gestures</code></div>
        <div class="ep-body">Query: <code>vocabulary_id</code>, <code>gesture_type</code>, <code>source_dataset</code>, <code>hand</code>, <code>page</code>, <code>per_page</code></div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/gestures</code></div>
        <div class="ep-desc">Requires vocabulary to exist first.</div>
        <pre class="ep-json">{
    "vocabulary_id": 1,
    "landmarks": [[0.5, 0.3, 0.0], [0.6, 0.4, 0.1]],
    "hand": "right",
    "gesture_type": "static",
    "frame_count": 1,
    "confidence_score": 0.95,
    "source_dataset": "bisindo-v1",
    "contributor_id": "user-001"
}</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>vocabulary_id</td><td>int</td><td>Yes</td><td>FK sign_vocabularies</td></tr>
                <tr><td>landmarks</td><td>array</td><td>Yes</td><td>MediaPipe 21-point data</td></tr>
                <tr><td>hand</td><td>enum</td><td>No</td><td><code>left</code> | <code>right</code> | <code>both</code></td></tr>
                <tr><td>gesture_type</td><td>enum</td><td>No</td><td><code>static</code> | <code>dynamic</code></td></tr>
                <tr><td>frame_count</td><td>int</td><td>No</td><td>min 1</td></tr>
                <tr><td>landmark_sequence</td><td>array</td><td>No</td><td>for dynamic (array of frames)</td></tr>
                <tr><td>confidence_score</td><td>float</td><td>No</td><td>0.0 - 1.0</td></tr>
                <tr><td>source_dataset</td><td>string</td><td>No</td><td>max 255</td></tr>
                <tr><td>contributor_id</td><td>string</td><td>No</td><td>max 255</td></tr>
            </tbody>
        </table>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/gestures/bulk</code></div>
        <div class="ep-desc">Bulk import.</div>
        <pre class="ep-json">{
    "gestures": [
        { "vocabulary_id": 1, "landmarks": [[0.5,0.3,0.0]], "hand": "right", "gesture_type": "static" },
        { "vocabulary_id": 2, "landmarks": [[0.6,0.4,0.1]], "hand": "left", "gesture_type": "static" }
    ]
}</pre>
    </div>
    <div class="ep-card"><div class="ep-head"><span class="method get">GET</span><code>/api/gestures/{id}</code></div><div class="ep-body">With nested vocabulary.</div></div>
    <div class="ep-card"><div class="ep-head"><span class="method delete">DELETE</span><code>/api/gestures/{id}</code></div><div class="ep-body">No update endpoint for gestures.</div></div>

    {{-- 2. Users --}}
    <h3 class="ep-group">2. Data Pengguna &mdash; Jumlah pengguna (orang)</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/register</code><span class="auth-badge public">Public</span></div>
        <div class="ep-desc">Create a new user account. Returns user object + Bearer token.</div>
        <pre class="ep-json">{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "secret123",
    "password_confirmation": "secret123",
    "role": "user",
    "preferred_language": "bisindo"
}</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>name</td><td>string</td><td>Yes</td><td>max 255</td></tr>
                <tr><td>email</td><td>string</td><td>Yes</td><td>unique, valid email</td></tr>
                <tr><td>password</td><td>string</td><td>Yes</td><td>min 8 chars</td></tr>
                <tr><td>password_confirmation</td><td>string</td><td>Yes</td><td>must match password</td></tr>
                <tr><td>role</td><td>enum</td><td>No</td><td><code>user</code> | <code>admin</code> | <code>researcher</code> (default: user)</td></tr>
                <tr><td>preferred_language</td><td>enum</td><td>No</td><td><code>bisindo</code> | <code>asl</code> (default: bisindo)</td></tr>
            </tbody>
        </table>
        <div class="ep-note">Password is hashed automatically. Token returned as <code>"token": "1|abc..."</code></div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/login</code><span class="auth-badge public">Public</span></div>
        <div class="ep-desc">Login with existing account. Returns user object + Bearer token.</div>
        <pre class="ep-json">{
    "email": "admin@bisindo.app",
    "password": "password"
}</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>email</td><td>string</td><td>Yes</td><td>valid email</td></tr>
                <tr><td>password</td><td>string</td><td>Yes</td><td></td></tr>
            </tbody>
        </table>
        <div class="ep-note">Returns 401 on bad credentials, 403 if account is deactivated.</div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/user</code><span class="auth-badge protected">Auth Required</span></div>
        <div class="ep-desc">Get the authenticated user's profile.</div>
        <div class="ep-body">No body. Header: <code>Authorization: Bearer &lt;token&gt;</code></div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/logout</code><span class="auth-badge protected">Auth Required</span></div>
        <div class="ep-desc">Revoke the current access token.</div>
        <div class="ep-body">No body. Header: <code>Authorization: Bearer &lt;token&gt;</code></div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method delete">DELETE</span><code>/api/users/{id}</code></div>
        <div class="ep-body">Hapus pengguna beserta semua tokennya.</div>
    </div>
    <div class="ep-card">
        <div class="ep-body">
            <strong>Seeded accounts</strong> (available after <code>php artisan migrate --seed</code>):
            <table class="ep-fields" style="margin-top:8px">
                <thead><tr><th>Email</th><th>Role</th><th>Password</th></tr></thead>
                <tbody>
                    <tr><td><code>admin@bisindo.app</code></td><td>admin</td><td><code>password</code></td></tr>
                    <tr><td><code>researcher@bisindo.app</code></td><td>researcher</td><td><code>password</code></td></tr>
                    <tr><td><code>test@bisindo.app</code></td><td>user</td><td><code>password</code></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- 3. Vocabularies --}}
    <h3 class="ep-group">3. Data Kosakata Bahasa Isyarat &mdash; Jumlah kata (kata/item)</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/vocabularies</code></div>
        <div class="ep-body">Query: <code>language</code>, <code>type</code>, <code>category_id</code>, <code>search</code>, <code>active_only</code>, <code>page</code>, <code>per_page</code></div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/vocabularies</code></div>
        <div class="ep-desc">Requires category to exist first.</div>
        <pre class="ep-json">{
    "category_id": 1,
    "word": "A",
    "description": "Huruf A dalam BISINDO",
    "language": "bisindo",
    "type": "alphabet",
    "is_active": true
}</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>category_id</td><td>int</td><td>Yes</td><td>FK vocabulary_categories</td></tr>
                <tr><td>word</td><td>string</td><td>Yes</td><td>max 255</td></tr>
                <tr><td>description</td><td>string</td><td>No</td><td></td></tr>
                <tr><td>language</td><td>enum</td><td>Yes</td><td><code>bisindo</code> | <code>asl</code></td></tr>
                <tr><td>type</td><td>enum</td><td>Yes</td><td><code>alphabet</code> | <code>number</code> | <code>word</code> | <code>phrase</code></td></tr>
                <tr><td>image_path</td><td>string</td><td>No</td><td></td></tr>
                <tr><td>video_path</td><td>string</td><td>No</td><td></td></tr>
                <tr><td>is_active</td><td>bool</td><td>No</td><td>default true</td></tr>
            </tbody>
        </table>
        <div class="ep-note">word_count, character_count auto-calculated from word.</div>
    </div>
    <div class="ep-card"><div class="ep-head"><span class="method get">GET</span><code>/api/vocabularies/{id}</code></div><div class="ep-body">With category, gestures, translations, audioFiles.</div></div>
    <div class="ep-card"><div class="ep-head"><span class="method put">PUT</span><code>/api/vocabularies/{id}</code></div><div class="ep-body">Same fields, all optional.</div></div>
    <div class="ep-card"><div class="ep-head"><span class="method delete">DELETE</span><code>/api/vocabularies/{id}</code></div><div class="ep-body">Cascade deletes gestures, translations.</div></div>

    {{-- 4. Translations --}}
    <h3 class="ep-group">4. Data Terjemahan Teks &mdash; Jumlah karakter / kata (string)</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/translations</code></div>
        <div class="ep-body">Query: <code>vocabulary_id</code>, <code>source_language</code>, <code>target_language</code>, <code>page</code>, <code>per_page</code></div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/translations</code></div>
        <div class="ep-desc">Requires vocabulary to exist first.</div>
        <pre class="ep-json">{
    "vocabulary_id": 1,
    "source_language": "id",
    "target_language": "en",
    "source_text": "Halo",
    "translated_text": "Hello"
}</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>vocabulary_id</td><td>int</td><td>Yes</td><td>FK sign_vocabularies</td></tr>
                <tr><td>source_language</td><td>string</td><td>Yes</td><td>max 10</td></tr>
                <tr><td>target_language</td><td>string</td><td>Yes</td><td>max 10</td></tr>
                <tr><td>source_text</td><td>string</td><td>Yes</td><td></td></tr>
                <tr><td>translated_text</td><td>string</td><td>Yes</td><td></td></tr>
            </tbody>
        </table>
        <div class="ep-note">character_count auto-calculated from translated_text.</div>
    </div>
    <div class="ep-card"><div class="ep-head"><span class="method put">PUT</span><code>/api/translations/{id}</code></div><div class="ep-body">Only source_text, translated_text updatable. No show endpoint.</div></div>
    <div class="ep-card"><div class="ep-head"><span class="method delete">DELETE</span><code>/api/translations/{id}</code></div></div>

    {{-- 5. Audio --}}
    <h3 class="ep-group">5. Data Audio/Suara &mdash; Durasi (detik) / Ukuran file (MB)</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/audio</code></div>
        <div class="ep-body">Query: <code>vocabulary_id</code>, <code>language</code>, <code>type</code>, <code>page</code>, <code>per_page</code></div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/audio</code></div>
        <div class="ep-desc">File upload. Use <strong>form-data</strong> in Thunder Client.</div>
        <pre class="ep-json">// Thunder Client > Body > Form
file:            [select file] (wav/mp3/ogg/m4a, max 10MB)
vocabulary_id:   1
language:        id
transcript:      Halo
type:            reference</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>file</td><td>file</td><td>Yes</td><td>wav, mp3, ogg, m4a / max 10MB</td></tr>
                <tr><td>vocabulary_id</td><td>int</td><td>No</td><td>FK sign_vocabularies</td></tr>
                <tr><td>language</td><td>string</td><td>No</td><td>max 10, default <code>id</code></td></tr>
                <tr><td>transcript</td><td>string</td><td>No</td><td></td></tr>
                <tr><td>type</td><td>enum</td><td>No</td><td><code>tts_output</code> | <code>stt_input</code> | <code>reference</code></td></tr>
            </tbody>
        </table>
        <div class="ep-note">duration_seconds, file_size_mb auto-calculated.</div>
    </div>
    <div class="ep-card"><div class="ep-head"><span class="method get">GET</span><code>/api/audio/{id}</code></div><div class="ep-body">With nested vocabulary.</div></div>
    <div class="ep-card"><div class="ep-head"><span class="method delete">DELETE</span><code>/api/audio/{id}</code></div><div class="ep-body">Removes file from storage. No update.</div></div>

    {{-- 6. Categories --}}
    <h3 class="ep-group">6. Data Kategori Kosakata &mdash; Jumlah kategori (kategori)</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/categories</code></div>
        <div class="ep-body">Query: <code>language</code>, <code>active_only</code>, <code>page</code>, <code>per_page</code></div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/categories</code></div>
        <div class="ep-desc">Create category. Populate this first &mdash; vocabularies depend on it.</div>
        <pre class="ep-json">{
    "name": "Alfabet",
    "slug": "alfabet",
    "description": "Huruf A-Z dalam BISINDO",
    "language": "bisindo",
    "sort_order": 1,
    "is_active": true
}</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>name</td><td>string</td><td>Yes</td><td>max 255</td></tr>
                <tr><td>slug</td><td>string</td><td>Yes</td><td>unique, max 255</td></tr>
                <tr><td>description</td><td>string</td><td>No</td><td></td></tr>
                <tr><td>language</td><td>enum</td><td>Yes</td><td><code>bisindo</code> | <code>asl</code></td></tr>
                <tr><td>sort_order</td><td>int</td><td>No</td><td>min 0</td></tr>
                <tr><td>is_active</td><td>bool</td><td>No</td><td>default true</td></tr>
            </tbody>
        </table>
    </div>
    <div class="ep-card"><div class="ep-head"><span class="method get">GET</span><code>/api/categories/{id}</code></div><div class="ep-body">Returns category with nested vocabularies.</div></div>
    <div class="ep-card"><div class="ep-head"><span class="method put">PUT</span><code>/api/categories/{id}</code></div><div class="ep-body">Same fields as POST, all optional.</div></div>
    <div class="ep-card"><div class="ep-head"><span class="method delete">DELETE</span><code>/api/categories/{id}</code></div><div class="ep-body">Cascade deletes vocabularies.</div></div>

    {{-- 7. History --}}
    <h3 class="ep-group">7. Data Riwayat Terjemahan &mdash; Jumlah sesi (sesi/log)</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/history</code></div>
        <div class="ep-body">Query: <code>user_id</code>, <code>session_id</code>, <code>direction</code>, <code>page</code>, <code>per_page</code></div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/history</code></div>
        <pre class="ep-json">{
    "session_id": "sess-001",
    "direction": "sign_to_text",
    "input_data": "[landmark array]",
    "output_data": "Halo",
    "input_language": "bisindo",
    "output_language": "id",
    "confidence_score": 0.92,
    "duration_seconds": 1.5,
    "is_correct": true
}</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>user_id</td><td>int</td><td>No</td><td>FK users</td></tr>
                <tr><td>session_id</td><td>string</td><td>Yes</td><td>max 255</td></tr>
                <tr><td>direction</td><td>enum</td><td>Yes</td><td><code>sign_to_text</code> | <code>text_to_sign</code> | <code>speech_to_text</code> | <code>text_to_speech</code></td></tr>
                <tr><td>input_data</td><td>string</td><td>No</td><td></td></tr>
                <tr><td>output_data</td><td>string</td><td>No</td><td></td></tr>
                <tr><td>input_language</td><td>enum</td><td>Yes</td><td><code>bisindo</code> | <code>asl</code> | <code>id</code> | <code>en</code></td></tr>
                <tr><td>output_language</td><td>enum</td><td>Yes</td><td><code>bisindo</code> | <code>asl</code> | <code>id</code> | <code>en</code></td></tr>
                <tr><td>confidence_score</td><td>float</td><td>No</td><td>0.0 - 1.0</td></tr>
                <tr><td>duration_seconds</td><td>float</td><td>No</td><td>min 0</td></tr>
                <tr><td>is_correct</td><td>bool</td><td>No</td><td></td></tr>
            </tbody>
        </table>
    </div>
    <div class="ep-card"><div class="ep-head"><span class="method get">GET</span><code>/api/history/{id}</code></div><div class="ep-body">With nested feedbacks.</div></div>
    <div class="ep-card"><div class="ep-head"><span class="method delete">DELETE</span><code>/api/history/{id}</code></div><div class="ep-body">Hapus riwayat terjemahan.</div></div>

    {{-- 8. AI Models --}}
    <h3 class="ep-group">8. Model AI/ML &mdash; Tingkat akurasi (%)</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/models</code></div>
        <div class="ep-body">Query: <code>type</code>, <code>language</code>, <code>status</code>, <code>deployed_only</code>. Not paginated.</div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/models</code></div>
        <pre class="ep-json">{
    "name": "bisindo-alphabet-cnn",
    "version": "1.0.0",
    "type": "alphabet_classifier",
    "language": "bisindo",
    "accuracy_percent": 94.5,
    "num_classes": 26,
    "training_samples": 5000,
    "validation_samples": 1000,
    "training_config": { "epochs": 50, "batch_size": 32 },
    "metrics": { "precision": 0.94, "recall": 0.93 },
    "status": "ready",
    "notes": "Trained on BISINDO alphabet dataset v1"
}</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>name</td><td>string</td><td>Yes</td><td>max 255</td></tr>
                <tr><td>version</td><td>string</td><td>Yes</td><td>max 50</td></tr>
                <tr><td>type</td><td>enum</td><td>Yes</td><td><code>alphabet_classifier</code> | <code>word_classifier</code> | <code>stt_model</code> | <code>tts_model</code></td></tr>
                <tr><td>language</td><td>enum</td><td>Yes</td><td><code>bisindo</code> | <code>asl</code> | <code>id</code> | <code>en</code> | <code>universal</code></td></tr>
                <tr><td>accuracy_percent</td><td>float</td><td>No</td><td>0 - 100</td></tr>
                <tr><td>num_classes</td><td>int</td><td>No</td><td>min 1</td></tr>
                <tr><td>training_samples</td><td>int</td><td>No</td><td>min 0</td></tr>
                <tr><td>validation_samples</td><td>int</td><td>No</td><td>min 0</td></tr>
                <tr><td>training_config</td><td>object</td><td>No</td><td>JSON</td></tr>
                <tr><td>metrics</td><td>object</td><td>No</td><td>JSON</td></tr>
                <tr><td>status</td><td>enum</td><td>No</td><td><code>training</code> | <code>validating</code> | <code>ready</code> | <code>deployed</code> | <code>archived</code></td></tr>
                <tr><td>is_active</td><td>bool</td><td>No</td><td>default false</td></tr>
                <tr><td>notes</td><td>string</td><td>No</td><td></td></tr>
            </tbody>
        </table>
    </div>
    <div class="ep-card"><div class="ep-head"><span class="method get">GET</span><code>/api/models/{id}</code></div><div class="ep-body">With feedbacks + feedback_accuracy.</div></div>
    <div class="ep-card"><div class="ep-head"><span class="method put">PUT</span><code>/api/models/{id}</code></div><div class="ep-body">Same fields, all optional.</div></div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/models/{id}/upload</code></div>
        <div class="ep-desc">Upload model file. Use <strong>form-data</strong>.</div>
        <pre class="ep-json">// Thunder Client > Body > Form
file:   [select .tflite file] (max 100MB)</pre>
    </div>
    <div class="ep-card"><div class="ep-head"><span class="method post">POST</span><code>/api/models/{id}/deploy</code></div><div class="ep-body">No body. Deploys model, archives siblings of same type+language.</div></div>
    <div class="ep-card"><div class="ep-head"><span class="method delete">DELETE</span><code>/api/models/{id}</code></div><div class="ep-body">Removes model file from storage.</div></div>

    {{-- 9. Feedbacks --}}
    <h3 class="ep-group">9. Feedback Pengguna &mdash; Jumlah respons (benar/salah)</h3>
    <div class="ep-card">
        <div class="ep-head"><span class="method get">GET</span><code>/api/feedbacks</code></div>
        <div class="ep-body">Query: <code>user_id</code>, <code>ai_model_id</code>, <code>type</code>, <code>is_correct</code>, <code>page</code>, <code>per_page</code></div>
    </div>
    <div class="ep-card">
        <div class="ep-head"><span class="method post">POST</span><code>/api/feedbacks</code></div>
        <pre class="ep-json">{
    "translation_history_id": 1,
    "ai_model_id": 1,
    "type": "correction",
    "is_correct": false,
    "expected_output": "B",
    "rating": 3,
    "comment": "Model predicted A but the sign was B"
}</pre>
        <table class="ep-fields">
            <thead><tr><th>Field</th><th>Type</th><th>Req</th><th>Values</th></tr></thead>
            <tbody>
                <tr><td>user_id</td><td>int</td><td>No</td><td>FK users</td></tr>
                <tr><td>translation_history_id</td><td>int</td><td>No</td><td>FK translation_histories</td></tr>
                <tr><td>ai_model_id</td><td>int</td><td>No</td><td>FK ai_models</td></tr>
                <tr><td>type</td><td>enum</td><td>Yes</td><td><code>correction</code> | <code>rating</code> | <code>bug_report</code> | <code>suggestion</code></td></tr>
                <tr><td>is_correct</td><td>bool</td><td>No</td><td></td></tr>
                <tr><td>expected_output</td><td>string</td><td>No</td><td></td></tr>
                <tr><td>rating</td><td>int</td><td>No</td><td>1 - 5</td></tr>
                <tr><td>comment</td><td>string</td><td>No</td><td></td></tr>
                <tr><td>metadata</td><td>object</td><td>No</td><td>JSON</td></tr>
            </tbody>
        </table>
    </div>
    <div class="ep-card"><div class="ep-head"><span class="method get">GET</span><code>/api/feedbacks/{id}</code></div><div class="ep-body">Detail feedback.</div></div>
    <div class="ep-card"><div class="ep-head"><span class="method delete">DELETE</span><code>/api/feedbacks/{id}</code></div><div class="ep-body">Hapus feedback.</div></div>

    {{-- Populate Order --}}
    <h3 class="ep-group">Populate Order (Thunder Client)</h3>
    <div class="ep-card">
        <div class="ep-body">
            <p>Data has FK dependencies. Populate in this order:</p>
            <ol class="ep-order">
                <li><strong>POST /api/register</strong> &mdash; create a user account first (or use seeded accounts)</li>
                <li><strong>POST /api/login</strong> &mdash; get a Bearer token for authenticated endpoints</li>
                <li><strong>POST /api/categories</strong> &mdash; create categories first</li>
                <li><strong>POST /api/vocabularies</strong> &mdash; needs category_id</li>
                <li><strong>POST /api/gestures</strong> &mdash; needs vocabulary_id</li>
                <li><strong>POST /api/translations</strong> &mdash; needs vocabulary_id</li>
                <li><strong>POST /api/audio</strong> &mdash; needs vocabulary_id (optional)</li>
                <li><strong>POST /api/models</strong> &mdash; independent</li>
                <li><strong>POST /api/history</strong> &mdash; needs user_id (optional)</li>
                <li><strong>POST /api/feedbacks</strong> &mdash; needs translation_history_id / ai_model_id</li>
            </ol>
            <p style="margin-top:8px">
                <strong>Thunder Client setup:</strong><br>
                1. Environment variable: <code>@{{ base_url }}</code> = <code>https://xxxx.ngrok-free.app/api</code><br>
                2. Headers on all requests:<br>
                &nbsp;&nbsp;<code>Accept: application/json</code><br>
                &nbsp;&nbsp;<code>Content-Type: application/json</code> (JSON bodies)<br>
                &nbsp;&nbsp;<code>ngrok-skip-browser-warning: true</code><br>
                &nbsp;&nbsp;<code>Authorization: Bearer &lt;token&gt;</code> (for protected endpoints)<br>
                3. File uploads (audio, model) &rarr; Body &rarr; Form (not JSON).
            </p>
        </div>
    </div>

</div>
</div>{{-- /panel-endpoints --}}

<script>
const mainNames = ['data','endpoints'];
const subNames = ['gestures','users','vocabularies','translations','audio','categories','history','models','feedbacks'];

function setHash(main, sub) {
    var h = main;
    if (main === 'data' && sub) h += '/' + sub;
    history.replaceState(null, '', '#' + h);
}

function activateMain(name) {
    document.querySelectorAll('.main-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.main-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('panel-' + name).classList.add('active');
    var idx = mainNames.indexOf(name);
    if (idx >= 0) document.querySelectorAll('.main-tab')[idx].classList.add('active');
}

function activateSub(name) {
    document.querySelectorAll('.sub-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.sub-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('sub-' + name).classList.add('active');
    var idx = subNames.indexOf(name);
    if (idx >= 0) document.querySelectorAll('.sub-tab')[idx].classList.add('active');
}

function mainTab(name) {
    activateMain(name);
    var sub = name === 'data' ? getCurrentSub() : null;
    setHash(name, sub);
}

function subTab(name) {
    activateSub(name);
    setHash('data', name);
}

function getCurrentSub() {
    var el = document.querySelector('.sub-tab.active');
    if (!el) return subNames[0];
    var idx = Array.from(document.querySelectorAll('.sub-tab')).indexOf(el);
    return subNames[idx] || subNames[0];
}

function restoreFromHash() {
    var h = location.hash.replace('#','');
    if (!h) return;
    var parts = h.split('/');
    var main = parts[0];
    var sub = parts[1];
    if (mainNames.indexOf(main) >= 0) activateMain(main);
    if (sub && subNames.indexOf(sub) >= 0) activateSub(sub);
}

// On load: restore tab state from hash
restoreFromHash();
</script>

</body>
</html>

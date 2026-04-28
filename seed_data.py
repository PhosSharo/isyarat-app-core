"""
seed_data.py - Seeding script for bisindo-api
Populates all 9 data entities to reach 30+ entries each.
Uses the REST API endpoints. Data is in Indonesian and contextually consistent.

Usage:
    pip install requests
    python seed_data.py

Make sure the Laravel server is running at http://127.0.0.1:8000
"""

import requests
import random
import uuid
import time
import struct
import io
import sys

BASE_URL = "http://127.0.0.1:8000/api"
HEADERS = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

# ─── Helpers ───────────────────────────────────────────────────────────────────

def api(method, path, json_data=None, files=None, data=None):
    """Make an API request and return the response JSON."""
    url = f"{BASE_URL}{path}"
    h = {k: v for k, v in HEADERS.items()}
    if files:
        h.pop("Content-Type", None)
    try:
        resp = requests.request(method, url, headers=h, json=json_data, files=files, data=data, timeout=30)
        if resp.status_code >= 400:
            print(f"  [WARN] {method} {path} -> {resp.status_code}: {resp.text[:200]}")
            return None
        return resp.json()
    except Exception as e:
        print(f"  [ERROR] {method} {path} -> {e}")
        return None


def get_existing_count(path, key="data"):
    """Get total count from a paginated or list endpoint."""
    resp = api("GET", path)
    if resp is None:
        return 0
    if "meta" in resp and "total" in resp["meta"]:
        return resp["meta"]["total"]
    if key in resp:
        return len(resp[key])
    if isinstance(resp, list):
        return len(resp)
    return 0


def get_all_ids(path, key="data"):
    """Get all IDs from an endpoint."""
    ids = []
    page = 1
    while True:
        resp = api("GET", f"{path}?per_page=100&page={page}")
        if resp is None:
            break
        items = resp.get(key, resp if isinstance(resp, list) else [])
        if not items:
            break
        for item in items:
            if isinstance(item, dict) and "id" in item:
                ids.append(item["id"])
        if "meta" in resp and page >= resp["meta"].get("last_page", 1):
            break
        if "meta" not in resp:
            break
        page += 1
    return ids


def generate_wav_bytes(duration_sec=0.5, sample_rate=16000):
    """Generate a minimal valid WAV file in memory."""
    num_samples = int(sample_rate * duration_sec)
    samples = []
    for i in range(num_samples):
        t = i / sample_rate
        value = int(16000 * (0.5 * __import__('math').sin(2 * 3.14159 * 440 * t)))
        samples.append(struct.pack('<h', max(-32768, min(32767, value))))
    audio_data = b''.join(samples)
    buf = io.BytesIO()
    # WAV header
    data_size = len(audio_data)
    buf.write(b'RIFF')
    buf.write(struct.pack('<I', 36 + data_size))
    buf.write(b'WAVE')
    buf.write(b'fmt ')
    buf.write(struct.pack('<I', 16))       # chunk size
    buf.write(struct.pack('<H', 1))        # PCM
    buf.write(struct.pack('<H', 1))        # mono
    buf.write(struct.pack('<I', sample_rate))
    buf.write(struct.pack('<I', sample_rate * 2))  # byte rate
    buf.write(struct.pack('<H', 2))        # block align
    buf.write(struct.pack('<H', 16))       # bits per sample
    buf.write(b'data')
    buf.write(struct.pack('<I', data_size))
    buf.write(audio_data)
    buf.seek(0)
    return buf.getvalue()


# ─── 1. USERS (via /api/register) ─────────────────────────────────────────────

def seed_users(target=30):
    print("\n[1/9] Seeding Users...")
    existing = get_existing_count("/feedbacks?per_page=1")  # dummy, we check users differently
    # Get user count from dashboard stats
    resp = api("GET", "/dashboard/stats")
    existing_count = 0
    if resp and "data" in resp:
        for stat in resp["data"]:
            if "Pengguna" in stat.get("title", ""):
                existing_count = stat["rows"].get("Total Pengguna", 0)
                break

    # Fallback: try to count from register attempts
    if existing_count == 0:
        existing_count = 3  # seeded accounts

    needed = max(0, target - existing_count)
    if needed == 0:
        print(f"  Already have {existing_count} users, skipping.")
        return

    print(f"  Existing: {existing_count}, need {needed} more.")

    indonesian_names = [
        ("Andi Pratama", "andi.pratama"),
        ("Budi Santoso", "budi.santoso"),
        ("Citra Dewi", "citra.dewi"),
        ("Dian Permata", "dian.permata"),
        ("Eka Saputra", "eka.saputra"),
        ("Fitri Handayani", "fitri.handayani"),
        ("Gilang Ramadhan", "gilang.ramadhan"),
        ("Hana Safitri", "hana.safitri"),
        ("Irfan Hakim", "irfan.hakim"),
        ("Joko Widodo", "joko.widodo"),
        ("Kartika Sari", "kartika.sari"),
        ("Lukman Hakim", "lukman.hakim"),
        ("Maya Anggraeni", "maya.anggraeni"),
        ("Naufal Rizky", "naufal.rizky"),
        ("Olivia Putri", "olivia.putri"),
        ("Putra Aditya", "putra.aditya"),
        ("Qori Ramadhani", "qori.ramadhani"),
        ("Rina Marlina", "rina.marlina"),
        ("Surya Dharma", "surya.dharma"),
        ("Tari Wulandari", "tari.wulandari"),
        ("Umar Faruq", "umar.faruq"),
        ("Vina Panduwinata", "vina.panduwinata"),
        ("Wahyu Hidayat", "wahyu.hidayat"),
        ("Xena Putri", "xena.putri"),
        ("Yusuf Maulana", "yusuf.maulana"),
        ("Zahra Amelia", "zahra.amelia"),
        ("Arief Rahman", "arief.rahman"),
        ("Bayu Segara", "bayu.segara"),
        ("Cantika Maharani", "cantika.maharani"),
        ("Dimas Prayoga", "dimas.prayoga"),
    ]

    roles = ["user", "user", "user", "user", "researcher"]  # mostly users
    languages = ["bisindo", "bisindo", "bisindo", "asl"]  # mostly bisindo

    created = 0
    for i in range(needed):
        name, email_prefix = indonesian_names[i % len(indonesian_names)]
        # Add suffix if we loop around
        suffix = f"{i // len(indonesian_names)}" if i >= len(indonesian_names) else ""
        email = f"{email_prefix}{suffix}@bisindo.app"

        result = api("POST", "/register", {
            "name": name,
            "email": email,
            "password": "password123",
            "password_confirmation": "password123",
            "role": random.choice(roles),
            "preferred_language": random.choice(languages),
        })
        if result:
            created += 1
            print(f"  Created user: {name} ({email})")

    print(f"  Done: {created} users created.")


# ─── 2. CATEGORIES ─────────────────────────────────────────────────────────────

def seed_categories(target=30):
    print("\n[2/9] Seeding Categories...")
    existing_ids = get_all_ids("/categories")
    existing_count = len(existing_ids)

    needed = max(0, target - existing_count)
    if needed == 0:
        print(f"  Already have {existing_count} categories, skipping.")
        return existing_ids

    print(f"  Existing: {existing_count}, need {needed} more.")

    new_categories = [
        # BISINDO
        {"name": "Keluarga BISINDO", "slug": "bisindo-family", "description": "Isyarat anggota keluarga dalam BISINDO", "language": "bisindo", "sort_order": 6},
        {"name": "Warna BISINDO", "slug": "bisindo-colors", "description": "Isyarat warna dalam BISINDO", "language": "bisindo", "sort_order": 7},
        {"name": "Hewan BISINDO", "slug": "bisindo-animals", "description": "Isyarat nama hewan dalam BISINDO", "language": "bisindo", "sort_order": 8},
        {"name": "Makanan BISINDO", "slug": "bisindo-food", "description": "Isyarat nama makanan dalam BISINDO", "language": "bisindo", "sort_order": 9},
        {"name": "Tempat BISINDO", "slug": "bisindo-places", "description": "Isyarat nama tempat umum dalam BISINDO", "language": "bisindo", "sort_order": 10},
        {"name": "Waktu BISINDO", "slug": "bisindo-time", "description": "Isyarat keterangan waktu dalam BISINDO", "language": "bisindo", "sort_order": 11},
        {"name": "Emosi BISINDO", "slug": "bisindo-emotions", "description": "Isyarat ekspresi emosi dalam BISINDO", "language": "bisindo", "sort_order": 12},
        {"name": "Profesi BISINDO", "slug": "bisindo-professions", "description": "Isyarat nama profesi dalam BISINDO", "language": "bisindo", "sort_order": 13},
        {"name": "Transportasi BISINDO", "slug": "bisindo-transport", "description": "Isyarat alat transportasi dalam BISINDO", "language": "bisindo", "sort_order": 14},
        {"name": "Pendidikan BISINDO", "slug": "bisindo-education", "description": "Isyarat istilah pendidikan dalam BISINDO", "language": "bisindo", "sort_order": 15},
        # ASL
        {"name": "ASL Family", "slug": "asl-family", "description": "Family member signs in ASL", "language": "asl", "sort_order": 6},
        {"name": "ASL Colors", "slug": "asl-colors", "description": "Color signs in ASL", "language": "asl", "sort_order": 7},
        {"name": "ASL Animals", "slug": "asl-animals", "description": "Animal signs in ASL", "language": "asl", "sort_order": 8},
        {"name": "ASL Food", "slug": "asl-food", "description": "Food and drink signs in ASL", "language": "asl", "sort_order": 9},
        {"name": "ASL Places", "slug": "asl-places", "description": "Common place signs in ASL", "language": "asl", "sort_order": 10},
        {"name": "ASL Time", "slug": "asl-time", "description": "Time-related signs in ASL", "language": "asl", "sort_order": 11},
        {"name": "ASL Emotions", "slug": "asl-emotions", "description": "Emotion and feeling signs in ASL", "language": "asl", "sort_order": 12},
        {"name": "ASL Professions", "slug": "asl-professions", "description": "Profession signs in ASL", "language": "asl", "sort_order": 13},
        {"name": "ASL Transport", "slug": "asl-transport", "description": "Transportation signs in ASL", "language": "asl", "sort_order": 14},
        {"name": "ASL Education", "slug": "asl-education", "description": "Education-related signs in ASL", "language": "asl", "sort_order": 15},
    ]

    created = 0
    for cat in new_categories[:needed]:
        result = api("POST", "/categories", cat)
        if result:
            created += 1
            new_id = result.get("data", {}).get("id")
            if new_id:
                existing_ids.append(new_id)
            print(f"  Created category: {cat['name']}")

    print(f"  Done: {created} categories created.")
    return existing_ids


# ─── 3. VOCABULARIES ──────────────────────────────────────────────────────────

def seed_vocabularies(target=30):
    """Vocabularies already have 85 from seeder. Skip if >= target."""
    print("\n[3/9] Checking Vocabularies...")
    resp = api("GET", "/vocabularies?per_page=1")
    total = 0
    if resp and "meta" in resp:
        total = resp["meta"]["total"]

    if total >= target:
        print(f"  Already have {total} vocabularies (>= {target}), skipping.")
    else:
        print(f"  Have {total} vocabularies, but this should be 85 from seeder. Check your database.")

    return get_all_ids("/vocabularies")


# ─── 4. HAND GESTURES ─────────────────────────────────────────────────────────

def seed_gestures(vocab_ids, target=30):
    print("\n[4/9] Seeding Hand Gestures...")
    existing_count = 0
    resp = api("GET", "/gestures?per_page=1")
    if resp and "meta" in resp:
        existing_count = resp["meta"]["total"]

    needed = max(0, target - existing_count)
    if needed == 0:
        print(f"  Already have {existing_count} gestures, skipping.")
        return

    print(f"  Existing: {existing_count}, need {needed} more.")

    if not vocab_ids:
        print("  [WARN] No vocabulary IDs available, cannot create gestures.")
        return

    datasets = [
        "bisindo_alphabet_roboflow_v2",
        "asl_alphabet_kaggle_87k",
        "bisindo_manual_collection_v1",
        "asl_wlasl_top100",
        "bisindo_ugm_dataset",
    ]
    contributors = [
        "peneliti-001", "peneliti-002", "peneliti-003",
        "mahasiswa-ui-01", "mahasiswa-ugm-01", "mahasiswa-its-01",
        "volunteer-jkt-01", "volunteer-sby-01", "volunteer-bdg-01",
    ]
    hands = ["left", "right", "both"]
    gesture_types = ["static", "static", "static", "dynamic"]  # mostly static

    def make_landmarks():
        """Generate realistic MediaPipe 21-point hand landmarks (x, y, z)."""
        landmarks = []
        # Wrist
        base_x, base_y = random.uniform(0.3, 0.7), random.uniform(0.5, 0.8)
        landmarks.append([round(base_x, 4), round(base_y, 4), round(random.uniform(-0.05, 0.05), 4)])
        # 20 finger joints (4 per finger x 5 fingers)
        for finger in range(5):
            for joint in range(4):
                dx = random.uniform(-0.15, 0.15) + (finger - 2) * 0.06
                dy = -random.uniform(0.02, 0.12) * (joint + 1)
                dz = random.uniform(-0.03, 0.03)
                landmarks.append([
                    round(base_x + dx, 4),
                    round(base_y + dy, 4),
                    round(dz, 4),
                ])
        return landmarks

    created = 0
    for i in range(needed):
        vid = random.choice(vocab_ids)
        gtype = random.choice(gesture_types)
        hand = random.choice(hands)
        frame_count = 1 if gtype == "static" else random.randint(15, 60)

        gesture_data = {
            "vocabulary_id": vid,
            "landmarks": make_landmarks(),
            "hand": hand,
            "gesture_type": gtype,
            "frame_count": frame_count,
            "confidence_score": round(random.uniform(0.70, 0.99), 3),
            "source_dataset": random.choice(datasets),
            "contributor_id": random.choice(contributors),
        }

        if gtype == "dynamic":
            gesture_data["landmark_sequence"] = [make_landmarks() for _ in range(frame_count)]

        result = api("POST", "/gestures", gesture_data)
        if result:
            created += 1
            if created % 10 == 0 or created == needed:
                print(f"  Created {created}/{needed} gestures...")

    print(f"  Done: {created} gestures created.")


# ─── 5. TEXT TRANSLATIONS ──────────────────────────────────────────────────────

def seed_translations(vocab_ids, target=30):
    print("\n[5/9] Seeding Text Translations...")
    existing_count = 0
    resp = api("GET", "/translations?per_page=1")
    if resp and "meta" in resp:
        existing_count = resp["meta"]["total"]

    needed = max(0, target - existing_count)
    if needed == 0:
        print(f"  Already have {existing_count} translations, skipping.")
        return

    print(f"  Existing: {existing_count}, need {needed} more.")

    if not vocab_ids:
        print("  [WARN] No vocabulary IDs available, cannot create translations.")
        return

    # Get vocabulary details to make translations consistent
    vocab_details = []
    for vid in vocab_ids[:50]:  # sample up to 50
        resp = api("GET", f"/vocabularies/{vid}")
        if resp and "data" in resp:
            vocab_details.append(resp["data"])

    if not vocab_details:
        print("  [WARN] Could not fetch vocabulary details.")
        return

    created = 0
    used_pairs = set()
    for i in range(needed):
        vocab = vocab_details[i % len(vocab_details)]
        word = vocab.get("word", "Halo")
        lang = vocab.get("language", "bisindo")

        # Create id -> en translation for BISINDO, en -> id for ASL
        if lang == "bisindo":
            src_lang, tgt_lang = "id", "en"
            # Indonesian to English translations
            id_to_en = {
                "A": "A", "B": "B", "C": "C", "D": "D", "E": "E", "F": "F",
                "G": "G", "H": "H", "I": "I", "J": "J", "K": "K", "L": "L",
                "M": "M", "N": "N", "O": "O", "P": "P", "Q": "Q", "R": "R",
                "S": "S", "T": "T", "U": "U", "V": "V", "W": "W", "X": "X",
                "Y": "Y", "Z": "Z",
                "0": "0", "1": "1", "2": "2", "3": "3", "4": "4",
                "5": "5", "6": "6", "7": "7", "8": "8", "9": "9",
                "Apa": "What", "Bagaimana": "How", "Baik": "Good/Fine",
                "Bisa": "Can/Able", "Halo": "Hello", "Kamu": "You",
                "Makan": "Eat", "Mau": "Want", "Minum": "Drink",
                "Nama": "Name", "Saya": "I/Me", "Terima Kasih": "Thank You",
                "Tolong": "Please/Help",
            }
            source_text = f"Isyarat BISINDO untuk '{word}'"
            translated = id_to_en.get(word, word)
            translated_text = f"BISINDO sign for '{translated}'"
        else:
            src_lang, tgt_lang = "en", "id"
            source_text = f"ASL sign for '{word}'"
            translated_text = f"Isyarat ASL untuk '{word}'"

        pair_key = (vocab["id"], src_lang, tgt_lang)
        if pair_key in used_pairs:
            # Try reverse direction
            src_lang, tgt_lang = tgt_lang, src_lang
            source_text, translated_text = translated_text, source_text
            pair_key = (vocab["id"], src_lang, tgt_lang)
            if pair_key in used_pairs:
                continue

        used_pairs.add(pair_key)

        result = api("POST", "/translations", {
            "vocabulary_id": vocab["id"],
            "source_language": src_lang,
            "target_language": tgt_lang,
            "source_text": source_text,
            "translated_text": translated_text,
        })
        if result:
            created += 1
            if created % 10 == 0 or created == needed:
                print(f"  Created {created}/{needed} translations...")

    print(f"  Done: {created} translations created.")


# ─── 6. AUDIO FILES ───────────────────────────────────────────────────────────

def seed_audio(vocab_ids, target=30):
    print("\n[6/9] Seeding Audio Files...")
    existing_count = 0
    resp = api("GET", "/audio?per_page=1")
    if resp and "meta" in resp:
        existing_count = resp["meta"]["total"]

    needed = max(0, target - existing_count)
    if needed == 0:
        print(f"  Already have {existing_count} audio files, skipping.")
        return

    print(f"  Existing: {existing_count}, need {needed} more.")

    audio_types = ["reference", "tts_output", "stt_input"]
    languages = ["id", "id", "id", "en"]

    transcripts_id = [
        "Halo, apa kabar?", "Selamat pagi", "Terima kasih banyak",
        "Tolong bantu saya", "Nama saya Andi", "Saya mau makan",
        "Bagaimana caranya?", "Saya tidak mengerti", "Bisa ulangi?",
        "Selamat tinggal", "Sampai jumpa", "Maaf, permisi",
        "Saya senang bertemu kamu", "Apa ini?", "Di mana toilet?",
        "Berapa harganya?", "Saya butuh bantuan", "Tolong bicara pelan",
        "Saya tuli", "Saya bisa bahasa isyarat", "Huruf A",
        "Angka satu", "Selamat siang", "Selamat malam",
        "Iya, benar", "Tidak, salah", "Mungkin", "Saya lapar",
        "Saya haus", "Cuaca hari ini cerah",
    ]

    transcripts_en = [
        "Hello, how are you?", "Good morning", "Thank you very much",
        "Please help me", "My name is Andi", "I want to eat",
        "How do you do it?", "I don't understand", "Can you repeat?",
        "Goodbye", "See you later", "Excuse me",
        "Nice to meet you", "What is this?", "Where is the restroom?",
        "How much is it?", "I need help", "Please speak slowly",
        "I am deaf", "I know sign language", "Letter A",
        "Number one", "Good afternoon", "Good evening",
        "Yes, correct", "No, wrong", "Maybe", "I am hungry",
        "I am thirsty", "The weather is nice today",
    ]

    wav_data = generate_wav_bytes(duration_sec=random.uniform(0.3, 1.5))

    created = 0
    for i in range(needed):
        lang = random.choice(languages)
        transcript = transcripts_id[i % len(transcripts_id)] if lang == "id" else transcripts_en[i % len(transcripts_en)]
        atype = random.choice(audio_types)
        vid = random.choice(vocab_ids) if vocab_ids and random.random() > 0.2 else None

        # Generate a fresh wav for each
        wav_data = generate_wav_bytes(duration_sec=random.uniform(0.3, 2.0))

        form_data = {
            "language": lang,
            "transcript": transcript,
            "type": atype,
        }
        if vid:
            form_data["vocabulary_id"] = str(vid)

        files = {
            "file": (f"audio_seed_{i+1}.wav", wav_data, "audio/wav"),
        }

        result = api("POST", "/audio", files=files, data=form_data)
        if result:
            created += 1
            if created % 10 == 0 or created == needed:
                print(f"  Created {created}/{needed} audio files...")

    print(f"  Done: {created} audio files created.")


# ─── 7. AI MODELS ─────────────────────────────────────────────────────────────

def seed_models(target=30):
    print("\n[7/9] Seeding AI Models...")
    resp = api("GET", "/models")
    existing = resp.get("data", []) if resp else []
    existing_count = len(existing)
    existing_names = {(m["name"], m["version"]) for m in existing}

    needed = max(0, target - existing_count)
    if needed == 0:
        print(f"  Already have {existing_count} models, skipping.")
        return [m["id"] for m in existing]

    print(f"  Existing: {existing_count}, need {needed} more.")

    model_templates = [
        # Alphabet classifiers - various versions and languages
        {"name": "bisindo_alphabet_cnn", "type": "alphabet_classifier", "language": "bisindo",
         "num_classes": 36, "arch": "CNN-2D", "dataset": "roboflow_bisindov2"},
        {"name": "bisindo_alphabet_lstm", "type": "alphabet_classifier", "language": "bisindo",
         "num_classes": 36, "arch": "LSTM", "dataset": "bisindo_ugm_v1"},
        {"name": "bisindo_alphabet_transformer", "type": "alphabet_classifier", "language": "bisindo",
         "num_classes": 36, "arch": "Transformer-Tiny", "dataset": "bisindo_combined_v1"},
        {"name": "asl_alphabet_cnn", "type": "alphabet_classifier", "language": "asl",
         "num_classes": 29, "arch": "CNN-2D", "dataset": "asl_alphabet_kaggle_87k"},
        {"name": "asl_alphabet_resnet", "type": "alphabet_classifier", "language": "asl",
         "num_classes": 29, "arch": "ResNet-18", "dataset": "asl_alphabet_kaggle_87k"},
        {"name": "asl_alphabet_mobilenet", "type": "alphabet_classifier", "language": "asl",
         "num_classes": 29, "arch": "MobileNetV3-Small", "dataset": "asl_alphabet_kaggle_87k"},

        # Word classifiers
        {"name": "bisindo_words_siformer", "type": "word_classifier", "language": "bisindo",
         "num_classes": 50, "arch": "Siformer-lite", "dataset": "wl_bisindo_50words"},
        {"name": "bisindo_words_lstm", "type": "word_classifier", "language": "bisindo",
         "num_classes": 32, "arch": "Bi-LSTM", "dataset": "wl_bisindo_32words"},
        {"name": "bisindo_words_tcn", "type": "word_classifier", "language": "bisindo",
         "num_classes": 32, "arch": "TCN", "dataset": "wl_bisindo_32words"},
        {"name": "asl_words_siformer_v2", "type": "word_classifier", "language": "asl",
         "num_classes": 200, "arch": "Siformer", "dataset": "wlasl_top200"},
        {"name": "asl_words_i3d", "type": "word_classifier", "language": "asl",
         "num_classes": 100, "arch": "I3D", "dataset": "wlasl_top100"},
        {"name": "asl_words_stgcn", "type": "word_classifier", "language": "asl",
         "num_classes": 100, "arch": "ST-GCN", "dataset": "wlasl_top100"},

        # STT models
        {"name": "stt_id_whisper_tiny", "type": "stt_model", "language": "id",
         "num_classes": None, "arch": "Whisper-Tiny", "dataset": "common_voice_id_v13"},
        {"name": "stt_id_wav2vec", "type": "stt_model", "language": "id",
         "num_classes": None, "arch": "Wav2Vec2-XLSR", "dataset": "common_voice_id_v13"},
        {"name": "stt_en_whisper_tiny", "type": "stt_model", "language": "en",
         "num_classes": None, "arch": "Whisper-Tiny", "dataset": "librispeech_clean"},
        {"name": "stt_universal_whisper", "type": "stt_model", "language": "universal",
         "num_classes": None, "arch": "Whisper-Small", "dataset": "multilingual_librispeech"},

        # TTS models
        {"name": "tts_id_tacotron", "type": "tts_model", "language": "id",
         "num_classes": None, "arch": "Tacotron2", "dataset": "indonesian_tts_v1"},
        {"name": "tts_id_vits", "type": "tts_model", "language": "id",
         "num_classes": None, "arch": "VITS", "dataset": "indonesian_tts_v2"},
        {"name": "tts_en_vits", "type": "tts_model", "language": "en",
         "num_classes": None, "arch": "VITS", "dataset": "ljspeech"},
        {"name": "tts_universal_xtts", "type": "tts_model", "language": "universal",
         "num_classes": None, "arch": "XTTS-v2", "dataset": "multilingual_tts_v1"},

        # More versions of existing models
        {"name": "bisindo_alphabet", "type": "alphabet_classifier", "language": "bisindo",
         "num_classes": 36, "arch": "Dense NN v2", "dataset": "roboflow_bisindov2_augmented", "version_override": "0.2.0"},
        {"name": "asl_alphabet", "type": "alphabet_classifier", "language": "asl",
         "num_classes": 29, "arch": "Dense NN v2", "dataset": "asl_alphabet_kaggle_87k_augmented", "version_override": "0.2.0"},
        {"name": "bisindo_words", "type": "word_classifier", "language": "bisindo",
         "num_classes": 32, "arch": "Siformer-lite v2", "dataset": "wl_bisindo_32words_augmented", "version_override": "0.2.0"},
        {"name": "asl_words", "type": "word_classifier", "language": "asl",
         "num_classes": 100, "arch": "Siformer v2", "dataset": "wlasl_top100_augmented", "version_override": "0.2.0"},
        {"name": "bisindo_alphabet", "type": "alphabet_classifier", "language": "bisindo",
         "num_classes": 36, "arch": "Dense NN v3", "dataset": "roboflow_bisindov3", "version_override": "0.3.0"},
        {"name": "asl_alphabet", "type": "alphabet_classifier", "language": "asl",
         "num_classes": 29, "arch": "Dense NN v3", "dataset": "asl_combined_v2", "version_override": "0.3.0"},
    ]

    statuses = ["training", "training", "validating", "ready", "archived"]

    created = 0
    model_ids = [m["id"] for m in existing]

    for tmpl in model_templates[:needed]:
        version = tmpl.get("version_override", f"0.{random.randint(1,5)}.0")
        name = tmpl["name"]

        if (name, version) in existing_names:
            continue

        accuracy = round(random.uniform(60, 98), 2) if random.random() > 0.3 else None
        training_samples = random.randint(1000, 50000)
        validation_samples = int(training_samples * random.uniform(0.15, 0.25))
        status = random.choice(statuses)

        model_data = {
            "name": name,
            "version": version,
            "type": tmpl["type"],
            "language": tmpl["language"],
            "accuracy_percent": accuracy,
            "num_classes": tmpl["num_classes"],
            "training_samples": training_samples,
            "validation_samples": validation_samples,
            "training_config": {
                "architecture": tmpl["arch"],
                "dataset": tmpl["dataset"],
                "epochs": random.randint(20, 100),
                "batch_size": random.choice([16, 32, 64]),
                "learning_rate": random.choice([0.001, 0.0005, 0.0001]),
                "optimizer": random.choice(["Adam", "AdamW", "SGD"]),
            },
            "metrics": {
                "precision": round(random.uniform(0.7, 0.99), 3) if accuracy else None,
                "recall": round(random.uniform(0.7, 0.99), 3) if accuracy else None,
                "f1_score": round(random.uniform(0.7, 0.99), 3) if accuracy else None,
            } if accuracy else None,
            "status": status,
            "is_active": status == "ready",
            "notes": f"Model {name} v{version} - {tmpl['arch']} trained on {tmpl['dataset']}",
        }

        result = api("POST", "/models", model_data)
        if result:
            created += 1
            mid = result.get("data", {}).get("id")
            if mid:
                model_ids.append(mid)
                existing_names.add((name, version))
            print(f"  Created model: {name} v{version} ({tmpl['type']})")

    print(f"  Done: {created} models created.")
    return model_ids


# ─── 8. TRANSLATION HISTORY ───────────────────────────────────────────────────

def seed_history(user_ids, target=30):
    print("\n[8/9] Seeding Translation History...")
    existing_count = 0
    resp = api("GET", "/history?per_page=1")
    if resp and "meta" in resp:
        existing_count = resp["meta"]["total"]

    needed = max(0, target - existing_count)
    if needed == 0:
        print(f"  Already have {existing_count} history entries, skipping.")
        return get_all_ids("/history")

    print(f"  Existing: {existing_count}, need {needed} more.")

    directions = ["sign_to_text", "text_to_sign", "speech_to_text", "text_to_speech"]
    lang_pairs = {
        "sign_to_text": [("bisindo", "id"), ("asl", "en")],
        "text_to_sign": [("id", "bisindo"), ("en", "asl")],
        "speech_to_text": [("id", "id"), ("en", "en")],
        "text_to_speech": [("id", "id"), ("en", "en")],
    }

    input_outputs = {
        "sign_to_text": [
            ("[[0.5,0.3,0.0],[0.6,0.4,0.1]...]", "Halo"),
            ("[[0.4,0.2,0.0],[0.5,0.3,0.1]...]", "Terima Kasih"),
            ("[[0.6,0.5,0.0],[0.7,0.6,0.1]...]", "Saya"),
            ("[[0.3,0.4,0.0],[0.4,0.5,0.1]...]", "Makan"),
            ("[[0.5,0.6,0.0],[0.6,0.7,0.1]...]", "Tolong"),
            ("[[0.45,0.35,0.0],[0.55,0.45,0.1]...]", "Apa"),
            ("[[0.55,0.45,0.0],[0.65,0.55,0.1]...]", "Nama"),
            ("[[0.35,0.25,0.0],[0.45,0.35,0.1]...]", "Baik"),
        ],
        "text_to_sign": [
            ("Selamat pagi", "gesture_sequence_pagi_001"),
            ("Apa kabar?", "gesture_sequence_kabar_001"),
            ("Terima kasih banyak", "gesture_sequence_terimakasih_001"),
            ("Tolong bantu saya", "gesture_sequence_tolong_001"),
            ("Nama saya siapa?", "gesture_sequence_nama_001"),
            ("Saya mau minum", "gesture_sequence_minum_001"),
            ("Bagaimana caranya?", "gesture_sequence_bagaimana_001"),
            ("Saya tidak mengerti", "gesture_sequence_tidakmengerti_001"),
        ],
        "speech_to_text": [
            ("audio_blob_halo.wav", "Halo, apa kabar?"),
            ("audio_blob_pagi.wav", "Selamat pagi"),
            ("audio_blob_terima.wav", "Terima kasih"),
            ("audio_blob_tolong.wav", "Tolong bantu saya"),
            ("audio_blob_nama.wav", "Nama saya Andi"),
            ("audio_blob_makan.wav", "Saya mau makan"),
            ("audio_blob_minum.wav", "Saya mau minum"),
            ("audio_blob_maaf.wav", "Maaf, saya tidak mengerti"),
        ],
        "text_to_speech": [
            ("Halo, selamat datang", "audio_output_halo.wav"),
            ("Terima kasih sudah membantu", "audio_output_terima.wav"),
            ("Selamat pagi, apa kabar?", "audio_output_pagi.wav"),
            ("Tolong ulangi sekali lagi", "audio_output_tolong.wav"),
            ("Saya senang bertemu kamu", "audio_output_senang.wav"),
            ("Sampai jumpa lagi", "audio_output_sampai.wav"),
            ("Maaf, bisa bicara pelan?", "audio_output_maaf.wav"),
            ("Saya belajar bahasa isyarat", "audio_output_belajar.wav"),
        ],
    }

    # Generate unique session IDs
    sessions = [f"sess-{uuid.uuid4().hex[:12]}" for _ in range(10)]

    created = 0
    history_ids = []

    for i in range(needed):
        direction = random.choice(directions)
        in_lang, out_lang = random.choice(lang_pairs[direction])
        io_pair = random.choice(input_outputs[direction])
        session = random.choice(sessions)
        uid = random.choice(user_ids) if user_ids and random.random() > 0.3 else None

        result = api("POST", "/history", {
            "user_id": uid,
            "session_id": session,
            "direction": direction,
            "input_data": io_pair[0],
            "output_data": io_pair[1],
            "input_language": in_lang,
            "output_language": out_lang,
            "confidence_score": round(random.uniform(0.5, 0.99), 3),
            "duration_seconds": round(random.uniform(0.3, 5.0), 2),
            "is_correct": random.choice([True, True, True, False, None]),
        })
        if result:
            created += 1
            hid = result.get("data", {}).get("id")
            if hid:
                history_ids.append(hid)
            if created % 10 == 0 or created == needed:
                print(f"  Created {created}/{needed} history entries...")

    print(f"  Done: {created} history entries created.")
    return history_ids


# ─── 9. USER FEEDBACKS ────────────────────────────────────────────────────────

def seed_feedbacks(user_ids, history_ids, model_ids, target=30):
    print("\n[9/9] Seeding User Feedbacks...")
    existing_count = 0
    resp = api("GET", "/feedbacks?per_page=1")
    if resp and "meta" in resp:
        existing_count = resp["meta"]["total"]

    needed = max(0, target - existing_count)
    if needed == 0:
        print(f"  Already have {existing_count} feedbacks, skipping.")
        return

    print(f"  Existing: {existing_count}, need {needed} more.")

    feedback_types = ["correction", "rating", "bug_report", "suggestion"]

    correction_comments = [
        "Model memprediksi huruf A tapi seharusnya B",
        "Isyarat 'Terima Kasih' terdeteksi sebagai 'Tolong'",
        "Prediksi angka 3 salah, seharusnya angka 8",
        "Kata 'Makan' terdeteksi sebagai 'Minum'",
        "Huruf D dan F sering tertukar",
        "Isyarat dinamis tidak terdeteksi dengan benar",
        "Confidence score terlalu tinggi untuk prediksi yang salah",
        "Model tidak bisa membedakan tangan kiri dan kanan",
    ]

    rating_comments = [
        "Aplikasi sangat membantu untuk belajar BISINDO",
        "Akurasi model sudah cukup baik untuk huruf",
        "Perlu peningkatan untuk kata-kata umum",
        "Fitur speech-to-text sangat berguna",
        "Respon cepat dan akurat",
        "Kadang lambat saat mendeteksi isyarat dinamis",
        "Sangat membantu komunikasi sehari-hari",
        "Perlu tambahan kosakata lebih banyak",
    ]

    bug_comments = [
        "Aplikasi crash saat kamera diputar",
        "Audio tidak terdengar di mode text-to-speech",
        "Landmark tangan tidak muncul di layar",
        "Prediksi berhenti setelah 30 detik",
        "Tidak bisa mendeteksi dua tangan sekaligus",
        "Error saat upload audio file besar",
        "Halaman riwayat tidak bisa di-scroll",
        "Notifikasi tidak muncul saat prediksi selesai",
    ]

    suggestion_comments = [
        "Tambahkan mode latihan untuk pemula",
        "Buat fitur kamus isyarat offline",
        "Tambahkan dukungan bahasa isyarat daerah",
        "Buat fitur video call dengan terjemahan real-time",
        "Tambahkan gamifikasi untuk motivasi belajar",
        "Buat komunitas pengguna dalam aplikasi",
        "Tambahkan fitur bookmark untuk isyarat favorit",
        "Buat mode gelap untuk kenyamanan mata",
    ]

    expected_outputs = [
        "B", "Terima Kasih", "8", "Makan", "D", "Halo",
        "Tolong", "Saya", "Nama", "Baik", "Minum", "Apa",
    ]

    created = 0
    for i in range(needed):
        ftype = random.choice(feedback_types)
        uid = random.choice(user_ids) if user_ids and random.random() > 0.2 else None
        hid = random.choice(history_ids) if history_ids and random.random() > 0.3 else None
        mid = random.choice(model_ids) if model_ids and random.random() > 0.3 else None

        feedback_data = {
            "user_id": uid,
            "translation_history_id": hid,
            "ai_model_id": mid,
            "type": ftype,
        }

        if ftype == "correction":
            feedback_data["is_correct"] = False
            feedback_data["expected_output"] = random.choice(expected_outputs)
            feedback_data["comment"] = random.choice(correction_comments)
            feedback_data["rating"] = random.randint(1, 3)
        elif ftype == "rating":
            feedback_data["is_correct"] = random.choice([True, True, True, False])
            feedback_data["rating"] = random.randint(1, 5)
            feedback_data["comment"] = random.choice(rating_comments)
        elif ftype == "bug_report":
            feedback_data["comment"] = random.choice(bug_comments)
            feedback_data["metadata"] = {
                "device": random.choice(["Samsung Galaxy A54", "iPhone 14", "Xiaomi Redmi Note 12", "OPPO A78"]),
                "os_version": random.choice(["Android 13", "Android 14", "iOS 17.2", "iOS 16.5"]),
                "app_version": random.choice(["1.0.0", "1.0.1", "1.1.0"]),
            }
        elif ftype == "suggestion":
            feedback_data["comment"] = random.choice(suggestion_comments)
            feedback_data["rating"] = random.randint(3, 5)

        result = api("POST", "/feedbacks", feedback_data)
        if result:
            created += 1
            if created % 10 == 0 or created == needed:
                print(f"  Created {created}/{needed} feedbacks...")

    print(f"  Done: {created} feedbacks created.")


# ─── MAIN ──────────────────────────────────────────────────────────────────────

def main():
    print("=" * 60)
    print("  BISINDO API - Data Seeding Script")
    print("=" * 60)
    print(f"  Target: {BASE_URL}")
    print(f"  Goal: 30 entries per entity")
    print()

    # Health check
    health = api("GET", "/health")
    if not health:
        print("[FATAL] Cannot reach API. Make sure Laravel is running:")
        print("  php artisan serve")
        sys.exit(1)
    print(f"[OK] API is healthy: {health.get('app', 'unknown')}")

    # 1. Users
    seed_users(target=30)

    # Collect user IDs for FK references
    user_ids = get_all_ids("/feedbacks?per_page=1")  # can't list users via API
    # Try to get user IDs from history or register responses
    # We'll use IDs 1-30 as approximation since we know they exist
    user_ids = list(range(1, 35))

    # 2. Categories
    seed_categories(target=30)

    # 3. Vocabularies (already 85, skip)
    vocab_ids = seed_vocabularies(target=30)

    # 4. Hand Gestures
    seed_gestures(vocab_ids, target=30)

    # 5. Text Translations
    seed_translations(vocab_ids, target=30)

    # 6. Audio Files
    seed_audio(vocab_ids, target=30)

    # 7. AI Models
    model_ids = seed_models(target=30)

    # 8. Translation History
    history_ids = seed_history(user_ids, target=30)

    # 9. User Feedbacks
    seed_feedbacks(user_ids, history_ids, model_ids, target=30)

    print("\n" + "=" * 60)
    print("  Seeding complete!")
    print("=" * 60)

    # Final stats
    print("\nFinal counts:")
    stats = api("GET", "/dashboard/stats")
    if stats and "data" in stats:
        for stat in stats["data"]:
            title = stat.get("title", "")
            rows = stat.get("rows", {})
            first_val = list(rows.values())[0] if rows else "?"
            print(f"  {title}: {first_val}")


if __name__ == "__main__":
    main()

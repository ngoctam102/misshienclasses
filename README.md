<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Cubet Techno Labs](https://cubettech.com)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[Many](https://www.many.co.uk)**
-   **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
-   **[DevSquad](https://devsquad.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[OP.GG](https://op.gg)**
-   **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
-   **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Quy tắc Cấu trúc Database và Filament

## 1. Cấu trúc Database (5 bảng chính)

### 1.1. Bảng tests (Bài thi)

```sql
CREATE TABLE tests (
    id bigint PRIMARY KEY,
    title varchar(255),
    description text,
    test_type enum('reading', 'listening'),
    duration int, -- thời gian làm bài (phút)
    total_questions int,
    total_score int,
    is_published boolean default false,
    published_at timestamp null,
    created_at timestamp,
    updated_at timestamp,
    deleted_at timestamp null
);
```

### 1.2. Bảng passages (Đoạn văn - cho Reading)

```sql
CREATE TABLE passages (
    id bigint PRIMARY KEY,
    test_id bigint,
    title varchar(255),
    content text,
    order int,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE
);
```

### 1.3. Bảng audio_files (File âm thanh - cho Listening)

```sql
CREATE TABLE audio_files (
    id bigint PRIMARY KEY,
    test_id bigint,
    title varchar(255),
    file_path varchar(255),
    duration int, -- thời lượng audio (giây)
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE
);
```

### 1.4. Bảng questions (Câu hỏi)

```sql
CREATE TABLE questions (
    id bigint PRIMARY KEY,
    test_id bigint,
    passage_id bigint null, -- cho Reading
    audio_file_id bigint null, -- cho Listening
    type enum('multiple_choice', 'matching', 'fill_in_blank'),
    instruction text null, -- hướng dẫn cho câu hỏi
    question_text text,
    options json null, -- cho multiple choice
    correct_answer json,
    explanation text null,
    score int,
    order int,
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE,
    FOREIGN KEY (passage_id) REFERENCES passages(id) ON DELETE SET NULL,
    FOREIGN KEY (audio_file_id) REFERENCES audio_files(id) ON DELETE SET NULL
);
```

### 1.5. Bảng test_attempts (Kết quả làm bài)

```sql
CREATE TABLE test_attempts (
    id bigint PRIMARY KEY,
    user_id bigint,
    test_id bigint,
    passage_id bigint null,
    question_id bigint,
    user_answer json,
    is_correct boolean,
    score float,
    time_taken int, -- thời gian làm câu hỏi (giây)
    created_at timestamp,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (test_id) REFERENCES tests(id),
    FOREIGN KEY (passage_id) REFERENCES passages(id),
    FOREIGN KEY (question_id) REFERENCES questions(id)
);
```

### 1.6. Bảng highlights (Highlight text)

```sql
CREATE TABLE highlights (
    id bigint PRIMARY KEY,
    user_id bigint,
    test_id bigint,
    passage_id bigint null,
    audio_file_id bigint null,
    text_content text,
    start_offset int,
    end_offset int,
    color varchar(20), -- yellow, green, blue, pink
    style varchar(20), -- underline, strikethrough
    created_at timestamp,
    updated_at timestamp,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (test_id) REFERENCES tests(id),
    FOREIGN KEY (passage_id) REFERENCES passages(id) ON DELETE CASCADE,
    FOREIGN KEY (audio_file_id) REFERENCES audio_files(id) ON DELETE CASCADE
);
```

## 2. Cấu trúc Filament Resources

### 2.1. TestResource

-   Form:
    -   Thông tin cơ bản (title, description, type, duration)
    -   Trạng thái (is_published)
-   Relations:
    -   PassagesRelationManager (cho Reading)
    -   AudioFilesRelationManager (cho Listening)
    -   QuestionsRelationManager

### 2.2. PassageResource

-   Form:
    -   Thông tin đoạn văn (title, content)
    -   Thứ tự (order)
-   Relations:
    -   QuestionsRelationManager

### 2.3. AudioFileResource

-   Form:
    -   Thông tin file (title, file upload)
    -   Thời lượng (duration)
-   Relations:
    -   QuestionsRelationManager

### 2.4. QuestionResource

-   Form:
    -   Loại câu hỏi (type)
    -   Hướng dẫn (instruction)
    -   Nội dung câu hỏi (question_text)
    -   Tùy theo loại câu hỏi:
        -   Multiple Choice: options (repeater)
        -   Matching: matching_pairs (repeater)
        -   Fill in blank: correct_answer (text)
    -   Giải thích (explanation)
    -   Điểm số (score)
    -   Thứ tự (order)

## 3. Quy tắc nhập liệu

### 3.1. Reading Test

1. Tạo Test mới (type: reading)
2. Thêm Passage
3. Thêm Questions cho Passage:
    - Instruction: hướng dẫn cho câu hỏi
    - Question: nội dung câu hỏi
    - Answer: đáp án theo định dạng tương ứng

### 3.2. Listening Test

1. Tạo Test mới (type: listening)
2. Upload Audio File
3. Thêm Questions:
    - Instruction: hướng dẫn cho câu hỏi
    - Question: nội dung câu hỏi
    - Options/Answer: tùy loại câu hỏi

### 3.3. Các loại câu hỏi

1. Multiple Choice:
    - Options: 2-6 lựa chọn
    - Answer: chọn đáp án đúng
2. Matching:
    - Pairs: 2-6 cặp nối
    - Answer: cặp nối đúng
3. Fill in blank:
    - Question: có dấu **\_**
    - Answer: đáp án điền vào

### 3.4. Tính năng Highlight

1. Cấu trúc dữ liệu:

    - Lưu trong bảng highlights
    - Mỗi highlight gắn với user_id và test_id
    - Có thể gắn với passage_id (Reading) hoặc audio_file_id (Listening)

2. Chức năng:

    - Nút toggle bật/tắt chế độ highlight
    - Bôi đen text để tạo highlight
    - Menu hiển thị khi chọn text cho phép:
        - 4 màu highlight: yellow, green, blue, pink
        - 2 kiểu style: underline, strikethrough
        - Xóa highlight hiện tại
    - Nút "Xóa tất cả" để xóa toàn bộ highlight trong bài
    - Highlight được lưu và hiển thị lại khi học sinh quay lại bài test

3. API Endpoints:

    ```php
    // Lưu highlight mới
    POST /api/highlights

    // Lấy tất cả highlight của bài test
    GET /api/tests/{test_id}/highlights

    // Xóa một highlight
    DELETE /api/highlights/{id}

    // Xóa tất cả highlight của bài test
    DELETE /api/tests/{test_id}/highlights
    ```

4. Frontend Components:
    - HighlightToggleButton: Nút bật/tắt chế độ highlight
    - HighlightMenu: Menu hiển thị khi chọn text
    - HighlightList: Danh sách các highlight đã tạo
    - ClearAllButton: Nút xóa tất cả highlight

## 4. Quy tắc JSON

### 4.1. options (multiple choice)

```json
[{ "text": "Lựa chọn A" }, { "text": "Lựa chọn B" }]
```

### 4.2. correct_answer

-   Multiple choice: `"A"`
-   Matching: `["Câu 1-Đáp án 1", "Câu 2-Đáp án 2"]`
-   Fill in blank: `["đáp án"]`

### 4.3. user_answer (test_attempts)

-   Multiple choice: `"A"`
-   Matching: `["Câu 1-Đáp án 1", "Câu 2-Đáp án 2"]`
-   Fill in blank: `["đáp án"]`

## 5. Quy tắc đánh số

-   Test: tự động tính total_questions và total_score
-   Passage: order để sắp xếp thứ tự
-   Question: order để sắp xếp trong mỗi passage/audio
-   TestAttempt: time_taken để tính thời gian làm bài

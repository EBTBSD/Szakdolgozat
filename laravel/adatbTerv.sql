CREATE TABLE `users` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `surname` varchar(255),
  `lastname` varchar(255),
  `username` varchar(255),
  `email` varchar(255) UNIQUE,
  `password` varchar(255),
  `role` varchar(255),
  `remember_token` varchar(255) COMMENT 'Laravel "Maradjak bejelentkezve"',
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) PRIMARY KEY,
  `token` varchar(255),
  `created_at` timestamp
);

CREATE TABLE `personal_access_tokens` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `tokenable_type` varchar(255) COMMENT 'Pl. App\Models\User',
  `tokenable_id` integer,
  `name` varchar(255),
  `token` varchar(255) UNIQUE,
  `abilities` text,
  `last_used_at` timestamp,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `courses` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `creator_id` integer,
  `name` varchar(255),
  `type` varchar(255),
  `code` varchar(6) COMMENT 'Meghívó kód',
  `public` boolean,
  `img_path` varchar(255),
  `created_at` timestamp
);

CREATE TABLE `course_enrollments` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `user_id` integer,
  `course_id` integer,
  `enrolled_at` timestamp
);

CREATE TABLE `assignments` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `course_id` integer,
  `name` varchar(255),
  `description` text,
  `type` varchar(255),
  `max_point` integer,
  `deadline` timestamp,
  `accessible` boolean,
  `created_at` timestamp
);

CREATE TABLE `submissions` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `assignment_id` integer,
  `user_id` integer,
  `file_path` varchar(255),
  `content_text` text,
  `is_finished` boolean,
  `points` integer,
  `grade` integer,
  `teacher_comment` text,
  `submitted_at` timestamp,
  `graded_at` timestamp
);

CREATE TABLE `lessons` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `course_id` integer,
  `title` varchar(255),
  `content` text,
  `file_path` varchar(255),
  `video_url` varchar(255),
  `sort_order` integer,
  `is_published` boolean,
  `created_at` timestamp
);

CREATE TABLE `announcements` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `course_id` integer,
  `user_id` integer,
  `title` varchar(255),
  `message` text,
  `created_at` timestamp
);

CREATE TABLE `categories` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `slug` varchar(255),
  `created_at` timestamp
);

CREATE TABLE `notifications` (
  `id` char(36) PRIMARY KEY,
  `type` varchar(255),
  `notifiable_type` varchar(255),
  `notifiable_id` integer,
  `data` text,
  `read_at` timestamp,
  `created_at` timestamp
);

ALTER TABLE `personal_access_tokens` ADD FOREIGN KEY (`tokenable_id`) REFERENCES `users` (`id`);

ALTER TABLE `courses` ADD FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

ALTER TABLE `course_enrollments` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `course_enrollments` ADD FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

ALTER TABLE `assignments` ADD FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

ALTER TABLE `submissions` ADD FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`);

ALTER TABLE `submissions` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `lessons` ADD FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

ALTER TABLE `announcements` ADD FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

ALTER TABLE `announcements` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

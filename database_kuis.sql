CREATE DATABASE pw_kuis;
USE pw_kuis;

CREATE TABLE user (
	id_user INT PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(30) NOT NULL,
    password VARCHAR(30) NOT NULL
);

SELECT * FROM user;

CREATE TABLE kuis (
	id_kuis INT PRIMARY KEY AUTO_INCREMENT,
    id_pembuat INT,
	judul VARCHAR(30) NOT NULL,
    thumbnail VARCHAR(255),
    FOREIGN KEY(id_pembuat) REFERENCES user(id_user)
);

CREATE TABLE soal_kuis (
	id_soal INT PRIMARY KEY AUTO_INCREMENT,
    id_kuis INT,
    konten VARCHAR(255) NOT NULL,
    FOREIGN KEY(id_kuis) REFERENCES kuis(id_kuis)
);

CREATE TABLE jawaban_soal (
	id_jawaban INT PRIMARY KEY AUTO_INCREMENT,
    id_soal INT,
    konten VARCHAR(100) NOT NULL,
    jawaban_benar BOOL,
    FOREIGN KEY(id_soal) REFERENCES soal_kuis(id_soal)
);

CREATE TABLE hasil_kuis (
	id_hasil INT PRIMARY KEY AUTO_INCREMENT,
    id_kuis INT,
    nama VARCHAR(255),
    FOREIGN KEY(id_kuis) REFERENCES kuis(id_kuis)
);

CREATE TABLE detail_hasil_kuis (
	id_hasil INT PRIMARY KEY AUTO_INCREMENT,
    id_hasil_kuis INT,
    id_soal_kuis INT,
    id_jawaban INT,
    FOREIGN KEY(id_hasil_kuis) REFERENCES hasil_kuis(id_hasil),
    FOREIGN KEY(id_soal_kuis) REFERENCES soal_kuis(id_soal),
    FOREIGN KEY(id_jawaban) REFERENCES jawaban_soal(id_jawaban)
);

SHOW TABLES;
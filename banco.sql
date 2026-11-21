-- Rode este arquivo UMA vez no phpMyAdmin (aba "Importar" ou "SQL")
-- ou pelo terminal:  mysql -u root < banco.sql

SET NAMES utf8mb4;   -- garante os acentos

CREATE DATABASE IF NOT EXISTS saep_produtos
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE saep_produtos;

-- [NOVA COLUNA] 1/5 -> para criar uma coluna nova, adicione uma linha aqui
CREATE TABLE IF NOT EXISTS produtos (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nome       VARCHAR(100)  NOT NULL,
  categoria  VARCHAR(50)   NOT NULL,
  preco      DECIMAL(10,2) NOT NULL,
  quantidade INT           NOT NULL
);

-- Alguns produtos de exemplo
INSERT INTO produtos (nome, categoria, preco, quantidade) VALUES
  ('Teclado Mecânico', 'Periféricos', 289.90, 14),
  ('Mouse Sem Fio',    'Periféricos',  79.50, 32),
  ('Monitor 24"',      'Monitores',   899.00,  4),
  ('Cabo HDMI 2m',     'Cabos',        24.90, 60);

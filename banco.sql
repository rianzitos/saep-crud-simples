-- ============================================================
--  BANCO DE DADOS
--  Como rodar (uma vez):
--    phpMyAdmin > aba "Importar" > escolher este arquivo
--    ou no terminal:  mysql -u root < banco.sql
--
--  [ADAPTAR] Para outro assunto (alunos, clientes, livros...):
--    1) troque o nome do banco e da tabela
--    2) troque as colunas pelas que a prova pedir
--    3) troque os dados de exemplo no INSERT lá embaixo
--  (o README tem o passo a passo completo)
-- ============================================================

SET NAMES utf8mb4;   -- garante os acentos

-- [ADAPTAR] nome do banco (precisa ser o mesmo em conexao.php)
CREATE DATABASE IF NOT EXISTS saep_produtos
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE saep_produtos;

-- Apaga a tabela antiga para poder importar de novo depois de mexer nas colunas.
-- (CUIDADO: apaga os dados que estiverem nela)
DROP TABLE IF EXISTS produtos;

-- [ADAPTAR] nome da tabela e [NOVA COLUNA] 1/5 -> colunas
-- Cada coluna é:  nome  TIPO  [NOT NULL = obrigatório]
-- O "id" é a chave primária: o MySQL numera sozinho (1, 2, 3...).
--
-- Tipos mais usados:
--   VARCHAR(100)   texto curto (nome, e-mail, telefone, CPF)
--   TEXT           texto longo (descrição, observação)
--   INT            número inteiro (quantidade, idade)
--   DECIMAL(10,2)  número com casas decimais (preço, nota, salário)
--   DATE           data (nascimento, vencimento)
CREATE TABLE produtos (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nome       VARCHAR(100)  NOT NULL,
  categoria  VARCHAR(50)   NOT NULL,
  preco      DECIMAL(10,2) NOT NULL,
  quantidade INT           NOT NULL
);

-- [ADAPTAR] dados de exemplo (a ordem tem que ser a mesma das colunas entre parênteses)
INSERT INTO produtos (nome, categoria, preco, quantidade) VALUES
  ('Teclado Mecânico', 'Periféricos', 289.90, 14),
  ('Mouse Sem Fio',    'Periféricos',  79.50, 32),
  ('Monitor 24"',      'Monitores',   899.00,  4),
  ('Cabo HDMI 2m',     'Cabos',        24.90, 60);

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
--   TINYINT(1)     verdadeiro/falso: guarda 0 (não) ou 1 (sim). Ex.: ativo, concluído, disponível
--   TINYINT        inteiro bem pequeno: de -128 a 127 (ou de 0 a 255 com UNSIGNED). Ex.: idade, nota de 0 a 10
--
-- TINYINT na prática (exemplo: coluna "ativo", que começa como 1 = sim):
--   Declarar:   ativo TINYINT(1) NOT NULL DEFAULT 1
--               (lembre da VÍRGULA no fim da linha de cima, e de colocar no INSERT também)
--   Inserir:    é só o número, SEM aspas:   VALUES ('Mouse', 'Periféricos', 79.50, 32, 1)
--               (o MySQL também aceita TRUE e FALSE: eles viram 1 e 0)
--   Consultar:  SELECT * FROM produtos WHERE ativo = 1;    -- só os ativos
--   Atualizar:  UPDATE produtos SET ativo = 0 WHERE id = 3;   -- desativa o produto 3
--   Com a tabela já criada (sem perder dados):
--               ALTER TABLE produtos ADD ativo TINYINT(1) NOT NULL DEFAULT 1;
--   Nas páginas PHP: procure por "TINYINT" nos comentários (formulario, criar, editar, index).
CREATE TABLE produtos (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nome       VARCHAR(100)  NOT NULL,
  categoria  VARCHAR(50)   NOT NULL,
  preco      DECIMAL(10,2) NOT NULL,
  quantidade INT           NOT NULL
);

-- [ADAPTAR] dados de exemplo (a ordem tem que ser a mesma das colunas entre parênteses)
-- Com uma coluna TINYINT "ativo" no final, cada linha ganha um número: ('Mouse', ..., 32, 1)
INSERT INTO produtos (nome, categoria, preco, quantidade) VALUES
  ('Teclado Mecânico', 'Periféricos', 289.90, 14),
  ('Mouse Sem Fio',    'Periféricos',  79.50, 32),
  ('Monitor 24"',      'Monitores',   899.00,  4),
  ('Cabo HDMI 2m',     'Cabos',        24.90, 60);

-- ============================================================
-- LOGIN: tabela de usuários
-- Usuário padrão:  admin  /  admin123
-- A senha é guardada como HASH (password_hash do PHP), nunca em texto puro.
-- Outro usuário/senha:  php -r "echo password_hash('minhasenha', PASSWORD_DEFAULT);"
-- e cole o resultado na coluna "senha".
-- ============================================================
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
  id      INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50)  NOT NULL UNIQUE,
  senha   VARCHAR(255) NOT NULL
);

INSERT INTO usuarios (usuario, senha) VALUES
  ('admin', '$2y$10$ZmmwRJA2XPqFSROUrNSFKOb9sS1z9zIGmGuv4G1kCvHURfhh7Tc8S');

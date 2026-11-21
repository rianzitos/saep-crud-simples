// ============================================================
//  SERVIDOR + API + BANCO  (tudo neste arquivo)
//  Rodar:  npm install  e depois  npm start
// ============================================================
const express = require('express');
const { DatabaseSync } = require('node:sqlite'); // SQLite já vem no Node 22.13+
const path = require('path');

const app = express();
app.use(express.json());                                   // lê JSON do corpo das requisições
app.use(express.static(path.join(__dirname, 'public')));   // serve o site (pasta public)

// ---------- 1) BANCO DE DADOS ----------
const db = new DatabaseSync(path.join(__dirname, 'banco.db'));

// [NOVA COLUNA] 1/2 -> adicione a coluna aqui na tabela
// (se o banco.db já existir, apague o arquivo para ele ser recriado)
db.exec(`
  CREATE TABLE IF NOT EXISTS produtos (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    nome       TEXT    NOT NULL,
    categoria  TEXT    NOT NULL,
    preco      REAL    NOT NULL,
    quantidade INTEGER NOT NULL
  )
`);

// Se a tabela estiver vazia, coloca alguns produtos de exemplo
const { total } = db.prepare('SELECT COUNT(*) AS total FROM produtos').get();
if (total === 0) {
  const inserir = db.prepare(
    'INSERT INTO produtos (nome, categoria, preco, quantidade) VALUES (?, ?, ?, ?)'
  );
  inserir.run('Teclado Mecânico', 'Periféricos', 289.9, 14);
  inserir.run('Mouse Sem Fio', 'Periféricos', 79.5, 32);
  inserir.run('Monitor 24"', 'Monitores', 899.0, 4);
  inserir.run('Cabo HDMI 2m', 'Cabos', 24.9, 60);
}

// ---------- 2) VALIDAÇÃO ----------
// Recebe o que veio do formulário, limpa os dados e confere se estão corretos.
// Devolve { erro } se algo estiver errado, ou { dados } se estiver tudo certo.
function validar(corpo) {
  // [NOVA COLUNA] 2/2 -> adicione o campo aqui (o nome tem que ser igual ao da coluna)
  const dados = {
    nome: String(corpo.nome ?? '').trim(),
    categoria: String(corpo.categoria ?? '').trim(),
    preco: Number(corpo.preco),
    quantidade: Number(corpo.quantidade),
  };

  if (!dados.nome) return { erro: 'Informe o nome do produto.' };
  if (!dados.categoria) return { erro: 'Informe a categoria.' };
  if (!(dados.preco >= 0)) return { erro: 'O preço precisa ser um número maior ou igual a 0.' };
  if (!Number.isInteger(dados.quantidade) || dados.quantidade < 0) {
    return { erro: 'A quantidade precisa ser um número inteiro maior ou igual a 0.' };
  }
  return { dados };
}

// ---------- 3) ROTAS (CRUD) ----------

// READ - lista todos (aceita ?busca=texto para filtrar por nome ou categoria)
app.get('/api/produtos', (req, res) => {
  const busca = `%${req.query.busca ?? ''}%`;
  const produtos = db
    .prepare('SELECT * FROM produtos WHERE nome LIKE ? OR categoria LIKE ? ORDER BY id DESC')
    .all(busca, busca);
  res.json(produtos);
});

// CREATE - cadastra um produto novo
app.post('/api/produtos', (req, res) => {
  const { erro, dados } = validar(req.body);
  if (erro) return res.status(400).json({ erro });

  // As colunas e os "?" são montados a partir dos campos de "dados",
  // por isso não precisa mexer aqui quando criar uma coluna nova.
  const colunas = Object.keys(dados);
  const sql = `INSERT INTO produtos (${colunas.join(', ')})
               VALUES (${colunas.map(() => '?').join(', ')})`;
  const resultado = db.prepare(sql).run(...Object.values(dados));

  res.status(201).json({ id: Number(resultado.lastInsertRowid), ...dados });
});

// UPDATE - altera um produto existente
app.put('/api/produtos/:id', (req, res) => {
  const { erro, dados } = validar(req.body);
  if (erro) return res.status(400).json({ erro });

  const sql = `UPDATE produtos
               SET ${Object.keys(dados).map((c) => `${c} = ?`).join(', ')}
               WHERE id = ?`;
  const resultado = db.prepare(sql).run(...Object.values(dados), req.params.id);

  if (resultado.changes === 0) return res.status(404).json({ erro: 'Produto não encontrado.' });
  res.json({ id: Number(req.params.id), ...dados });
});

// DELETE - remove um produto
app.delete('/api/produtos/:id', (req, res) => {
  const resultado = db.prepare('DELETE FROM produtos WHERE id = ?').run(req.params.id);

  if (resultado.changes === 0) return res.status(404).json({ erro: 'Produto não encontrado.' });
  res.status(204).end();
});

// ---------- 4) LIGAR O SERVIDOR ----------
const PORTA = process.env.PORT || 3000;
app.listen(PORTA, () => console.log(`Rodando em http://localhost:${PORTA}`));

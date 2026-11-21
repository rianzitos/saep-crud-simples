# SAEP CRUD Simples

CRUD de **produtos** (cadastrar, listar, editar, excluir, buscar) com 5 temas de CSS.
Node + Express + SQLite. São só 5 arquivos de código.

## Como rodar

Precisa de Node **22.13 ou mais novo** (o SQLite já vem dentro dele).

```bash
npm install
npm start
```

Abra http://localhost:3000. O banco (`banco.db`) é criado sozinho, com 4 produtos de exemplo.

## Arquivos

| Arquivo | O que faz |
|---|---|
| `server.js` | Banco, validação e as 4 rotas da API |
| `public/index.html` | Estrutura da página (formulário e tabela) |
| `public/app.js` | Conversa com a API e desenha a tela |
| `public/style.css` | Os 5 temas |

## API

| Método | Rota | Ação |
|---|---|---|
| GET | `/api/produtos?busca=texto` | Lista (e filtra) |
| POST | `/api/produtos` | Cadastra |
| PUT | `/api/produtos/:id` | Altera |
| DELETE | `/api/produtos/:id` | Exclui |

## Como adicionar uma coluna nova (ex.: `marca`)

Procure por **`[NOVA COLUNA]`** no código. São 4 lugares:

1. `server.js` → na `CREATE TABLE`: `marca TEXT NOT NULL,`
2. `server.js` → na função `validar`: `marca: String(corpo.marca ?? '').trim(),`
3. `public/index.html` → no formulário: um `<label>` + `<input name="marca">`
4. `public/index.html` → um `<th>Marca</th>` na tabela e, em `public/app.js`, o `<td>` correspondente

Depois **apague o `banco.db`** e rode de novo (a tabela só é criada quando o arquivo não existe).
Se não puder apagar os dados, rode no lugar: `ALTER TABLE produtos ADD COLUMN marca TEXT DEFAULT ''`.

O INSERT, o UPDATE e o preenchimento do formulário na hora de editar já se adaptam sozinhos,
desde que o `name` do input seja igual ao nome da coluna.

## Como mudar para outro tipo de cadastro (ex.: clientes)

Troque `produtos` pelo novo nome e ajuste as colunas nos mesmos lugares acima.

## Os 5 temas

**Papel**, **Terminal**, **Oceano**, **Brutal** e **Floresta**. A escolha fica salva no navegador.
No `style.css`, cada tema é um bloco que só muda variáveis (`--fundo`, `--destaque`, `--raio`...).
Para criar outro: copie um bloco, mude os valores e adicione um botão no `index.html`.

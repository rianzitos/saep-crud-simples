# CRUD em PHP (exemplo: produtos)

PHP puro + MySQL (PDO). Sem framework e sem JavaScript (só o `confirm()` do botão Excluir).

## Como rodar (XAMPP)

1. Copie esta pasta para `C:\xampp\htdocs\saep-crud-simples` e ligue **Apache** e **MySQL**.
2. No phpMyAdmin, aba **Importar**, escolha o `banco.sql` e execute.
3. Abra http://localhost/saep-crud-simples/

Sem XAMPP: `mysql -u root < banco.sql` e depois `php -S localhost:8000` dentro da pasta.
Se o `root` do seu MySQL tiver senha, mude em `conexao.php`.

## Arquivos

| Arquivo | O que faz |
|---|---|
| `banco.sql` | Cria o banco, a tabela e dados de exemplo |
| `conexao.php` | Conexão com o banco, `h()` (escapa HTML) e `validar()` |
| `index.php` | **R**ead: lista os registros |
| `criar.php` | **C**reate: cadastra |
| `editar.php` | **U**pdate: altera (`editar.php?id=3`) |
| `excluir.php` | **D**elete: apaga (id por POST) |
| `formulario.php` | Formulário usado por `criar.php` e `editar.php` |
| `cabecalho.php` / `rodape.php` | Topo e fim de todas as páginas |
| `style.css` | **O** CSS do site (único arquivo de estilo) |
| `temas.css` | Os 5 estilos prontos, só para copiar (o site não usa este arquivo) |

Nos arquivos PHP, procure por estas etiquetas:

- **`[ADAPTAR]`**: tudo que tem o assunto "produto" (nome da tabela, títulos, textos).
- **`[NOVA COLUNA]`**: tudo que precisa mudar quando as colunas mudam (numeradas de 1/5 a 5/5).

---

## Adaptar para outro assunto (a prova pede alunos, clientes, livros...)

Exemplo: transformar **produtos** em **alunos** (nome, turma, nota).

### Passo 1: trocar as palavras (Localizar e Substituir)

No VS Code: `Ctrl+Shift+H` (substituir em todos os arquivos). Ative **"Aa" (diferenciar maiúsculas)** e substitua, nesta ordem:

| Trocar | Por |
|---|---|
| `produtos` | `alunos` |
| `produto` | `aluno` |
| `Produtos` | `Alunos` |
| `Produto` | `Aluno` |

Isso já muda: o nome da tabela e do banco (`saep_alunos`), as variáveis (`$aluno`, `$alunos`), os títulos e as mensagens.

Confira à mão os textos com gênero (palavras "Novo", "cadastrado", "excluído", "Nenhum", "Este"): para algo feminino como "turma" ou "matrícula", vira "Nova", "cadastrada", "excluída", "Nenhuma", "Esta". Os textos estão em `index.php`, `criar.php` e `editar.php`.

Troque também o nome do sistema em `cabecalho.php` (título da aba, `<h1>` e subtítulo, que ainda dizem "Estoque").

### Passo 2: trocar as colunas

Hoje as colunas são `nome`, `categoria`, `preco` e `quantidade`. Troque pelas da prova (ex.: `nome`, `turma`, `nota`). Siga as etiquetas `[NOVA COLUNA]`:

| # | Arquivo | O que fazer |
|---|---|---|
| 1/5 | `banco.sql` | Colunas do `CREATE TABLE` **e** colunas/valores do `INSERT` de exemplo |
| 2/5 | `conexao.php` | Regras em `validar()` (modelos de validação no comentário) |
| 3/5 | `formulario.php` | Um `<label>` + `<input>` por coluna (o `name` é igual ao nome da coluna; há modelos de data, e-mail, textarea e select no comentário) |
| 4/5 | `criar.php` e `editar.php` | O array do `$_POST`, o `INSERT` e o `UPDATE` (e o array inicial `$produto`, em `criar.php`) |
| 5/5 | `index.php` | Um `<th>` e um `<td>` por coluna, na mesma ordem |

Depois de mexer no `banco.sql`, **importe de novo** (ele apaga a tabela antiga e recria).

Dica: o `id` não aparece em formulário nenhum. O MySQL numera sozinho.

### Passo 3: tirar o que é só de estoque

- Em `index.php`, a etiqueta "baixo" (quantidade de 5 ou menos) e o `number_format` do preço. Se não tiver preço ou quantidade, apague.

### Tipo de coluna x tipo de campo

| Dado | Coluna no `banco.sql` | `<input>` no formulário |
|---|---|---|
| Texto curto | `VARCHAR(100)` | `type="text"` |
| Texto longo | `TEXT` | `<textarea>` |
| E-mail | `VARCHAR(100)` | `type="email"` |
| Inteiro | `INT` | `type="number" step="1"` |
| Decimal (preço, nota) | `DECIMAL(10,2)` | `type="number" step="0.01"` |
| Data | `DATE` | `type="date"` |
| Sim/Não (verdadeiro/falso) | `TINYINT(1)` | `type="checkbox"` (veja abaixo) |
| Número bem pequeno (idade, nota 0 a 10) | `TINYINT` | `type="number" step="1"` |
| Opções fixas | `VARCHAR(30)` | `<select>` |

### Coluna TINYINT (sim/não, 0 ou 1)

`TINYINT(1)` guarda **0 (não)** ou **1 (sim)**. Serve para "ativo", "concluído", "disponível" etc.
Exemplo com a coluna `ativo`. Procure por **`TINYINT`** nos comentários de cada arquivo:

| Arquivo | O que fazer |
|---|---|
| `banco.sql` | Na tabela: `ativo TINYINT(1) NOT NULL DEFAULT 1` (vírgula na linha de cima). No INSERT de exemplo, um `1` ou `0` **sem aspas** no fim de cada linha |
| `formulario.php` | `<label class="opcao"><input type="checkbox" name="ativo" value="1" <?= $produto['ativo'] ? 'checked' : '' ?>> Ativo</label>` |
| `criar.php` | No array inicial: `'ativo' => 1` (já abre marcado). No `$_POST`: `'ativo' => isset($_POST['ativo']) ? 1 : 0,`. Inclua `ativo` no `INSERT` |
| `editar.php` | No `$_POST`: `'ativo' => isset($_POST['ativo']) ? 1 : 0,`. Inclua `ativo = ?` no `UPDATE` |
| `index.php` | `<th>Ativo</th>` e `<td><?= $p['ativo'] ? 'Sim' : 'Não' ?></td>` |

Duas pegadinhas:

- O checkbox desmarcado **não é enviado** pelo navegador. Por isso o `isset(...) ? 1 : 0`.
- Passe sempre **número** (1 ou 0) para o banco, nunca `true`/`false`. O `false` vira texto vazio e o MySQL dá erro.

Comandos úteis no SQL: `INSERT ... VALUES ('Mouse', 1)` · `SELECT * FROM produtos WHERE ativo = 1` · `UPDATE produtos SET ativo = 0 WHERE id = 3`.
Se preferir uma lista "Sim/Não" em vez de checkbox, o modelo com `<select>` está no comentário do `formulario.php`.

### Se a prova pedir uma segunda tabela (ex.: alunos **e** turmas)

Copie `index.php`, `criar.php`, `editar.php`, `excluir.php` e `formulario.php` com outros nomes (ex.: `turmas.php`, `turma_criar.php`...), faça o Passo 1 e o Passo 2 nas cópias e crie a tabela nova no `banco.sql`. Lembre de atualizar os links (`href` e `header('Location: ...')`) para os novos nomes.

---

## Mudar o estilo (CSS)

O site usa **um único arquivo**: `style.css`. Ele tem 2 partes:

1. **Layout** (no começo): grade, tabela, botões, formulário. Não precisa mexer.
2. **Tema** (no final, entre as linhas `INÍCIO DO TEMA` e `FIM DO TEMA`): cores, fonte, bordas e cantos.

Para usar outro dos 5 estilos (**Papel, Terminal, Oceano, Brutal, Floresta**):

1. Abra o `temas.css` e copie o tema que quiser, **da linha de INÍCIO até a linha de FIM**.
2. No `style.css`, apague o tema que está no final (também de INÍCIO até FIM) e cole o novo no lugar.
3. Recarregue com `Ctrl+F5`.

Para um visual próprio, é só mudar os valores do bloco `:root` do tema. O `temas.css` explica o que cada variável faz.

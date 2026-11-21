# CRUD de Produtos em PHP

PHP puro + MySQL (PDO). Sem framework, sem JavaScript, sem instalar nada além do XAMPP.
Tem 5 estilos de CSS para trocar com um clique.

## Como rodar (XAMPP)

1. Copie esta pasta para `C:\xampp\htdocs\saep-crud-simples` e ligue **Apache** e **MySQL**.
2. No phpMyAdmin, aba **Importar**, escolha o `banco.sql` e execute (cria o banco `saep_produtos` com a tabela `produtos`).
3. Abra http://localhost/saep-crud-simples/

Sem XAMPP: rode `mysql -u root < banco.sql` e depois `php -S localhost:8000` dentro da pasta.

Se o seu MySQL tiver senha no `root`, mude em `conexao.php`.

## Arquivos

| Arquivo | O que faz |
|---|---|
| `banco.sql` | Cria o banco e a tabela |
| `conexao.php` | Conexão com o banco, `h()` (escapa HTML) e `validar()` |
| `index.php` | **R**ead: lista os produtos |
| `criar.php` | **C**reate: cadastra |
| `editar.php` | **U**pdate: altera (`editar.php?id=3`) |
| `excluir.php` | **D**elete: apaga (recebe o id por POST) |
| `formulario.php` | Formulário usado por `criar.php` e `editar.php` |
| `cabecalho.php` / `rodape.php` | Topo e fim de todas as páginas |
| `tema.php` | Guarda o tema escolhido num cookie |
| `css/base.css` | Layout comum a todos os temas |
| `css/papel.css`, `terminal.css`, `oceano.css`, `brutal.css`, `floresta.css` | Os 5 estilos |

## Adicionar uma coluna nova (ex.: `marca`)

Procure por **`[NOVA COLUNA]`** no código. São 5 lugares:

1. `banco.sql`: `marca VARCHAR(50) NOT NULL,`
   (com o banco já criado, rode no phpMyAdmin: `ALTER TABLE produtos ADD marca VARCHAR(50) NOT NULL DEFAULT '';`)
2. `conexao.php`: regra de validação na função `validar()` (se for obrigatório)
3. `formulario.php`: `<label>Marca <input name="marca" value="<?= h($produto['marca']) ?>"></label>`
4. `criar.php` e `editar.php`: pegar `$_POST['marca']`, e incluir a coluna no `INSERT` / `UPDATE`
5. `index.php`: um `<th>Marca</th>` e um `<td><?= h($p['marca']) ?></td>`

Em `criar.php`, o array inicial `$produto` também precisa da chave nova (`'marca' => ''`).

## Trocar de estilo / criar um 6º

O tema fica num cookie. Cada arquivo em `css/` só define variáveis de cor, fonte e borda.
Para criar outro: copie um desses arquivos, mude os valores e adicione o nome em `$temas`
(`cabecalho.php`) e em `$permitidos` (`tema.php`).

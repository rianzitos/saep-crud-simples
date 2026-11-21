// ============================================================
//  FRONT-END: conversa com a API e desenha a tela
// ============================================================
const API = '/api/produtos';

const form = document.getElementById('form');
const tabela = document.getElementById('tabela');
const msg = document.getElementById('msg');
let produtos = []; // última lista recebida da API

// ---------- utilidades ----------
const dinheiro = (n) => Number(n).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

// Evita que texto digitado vire HTML (segurança)
const esc = (texto) =>
  String(texto).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

function mostrarMsg(texto, erro = false) {
  msg.textContent = texto;
  msg.className = erro ? 'erro' : 'ok';
}

// ---------- READ: buscar e desenhar a lista ----------
async function carregar() {
  const busca = document.getElementById('busca').value;
  const resposta = await fetch(`${API}?busca=${encodeURIComponent(busca)}`);
  produtos = await resposta.json();
  desenhar();
}

function desenhar() {
  // [NOVA COLUNA] adicione um <td> aqui (mesma ordem do <th> no index.html)
  tabela.innerHTML = produtos.map((p) => `
    <tr>
      <td data-rotulo="Nome">${esc(p.nome)}</td>
      <td data-rotulo="Categoria">${esc(p.categoria)}</td>
      <td data-rotulo="Preço">${dinheiro(p.preco)}</td>
      <td data-rotulo="Qtd.">
        <span>${p.quantidade}${p.quantidade <= 5 ? '<em class="etiqueta">baixo</em>' : ''}</span>
      </td>
      <td class="botoes">
        <button class="btn-sec" data-acao="editar" data-id="${p.id}">Editar</button>
        <button class="btn-perigo" data-acao="excluir" data-id="${p.id}">Excluir</button>
      </td>
    </tr>
  `).join('');

  document.getElementById('vazio').hidden = produtos.length > 0;

  // Sugestões de categoria no campo do formulário
  const categorias = [...new Set(produtos.map((p) => p.categoria))];
  document.getElementById('lista-categorias').innerHTML =
    categorias.map((c) => `<option value="${esc(c)}">`).join('');

  // Resumo do topo
  document.getElementById('resumo-itens').textContent = produtos.length;
  document.getElementById('resumo-unidades').textContent =
    produtos.reduce((soma, p) => soma + p.quantidade, 0);
  document.getElementById('resumo-valor').textContent =
    dinheiro(produtos.reduce((soma, p) => soma + p.preco * p.quantidade, 0));
}

// ---------- CREATE / UPDATE: enviar o formulário ----------
form.addEventListener('submit', async (evento) => {
  evento.preventDefault(); // não deixa a página recarregar

  // Pega todos os campos do formulário pelo "name" (inclui colunas novas sozinho)
  const dados = Object.fromEntries(new FormData(form));
  const editando = dados.id !== '';

  const resposta = await fetch(editando ? `${API}/${dados.id}` : API, {
    method: editando ? 'PUT' : 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(dados),
  });

  if (!resposta.ok) {
    const { erro } = await resposta.json();
    return mostrarMsg(erro, true);
  }

  mostrarMsg(editando ? 'Produto atualizado!' : 'Produto cadastrado!');
  limparFormulario();
  carregar();
});

// ---------- botões da tabela: EDITAR e DELETE ----------
tabela.addEventListener('click', async (evento) => {
  const botao = evento.target.closest('button');
  if (!botao) return;

  const id = Number(botao.dataset.id);

  if (botao.dataset.acao === 'editar') {
    const produto = produtos.find((p) => p.id === id);
    // Preenche cada campo do formulário que tenha o mesmo name da coluna
    for (const [campo, valor] of Object.entries(produto)) {
      if (form.elements[campo]) form.elements[campo].value = valor;
    }
    document.getElementById('form-titulo').textContent = 'Editar produto';
    document.getElementById('btn-salvar').textContent = 'Salvar alterações';
    document.getElementById('btn-cancelar').hidden = false;
    form.elements.nome.focus();
    form.scrollIntoView({ behavior: 'smooth' });
  }

  if (botao.dataset.acao === 'excluir') {
    if (!confirm('Excluir este produto?')) return;
    await fetch(`${API}/${id}`, { method: 'DELETE' });
    mostrarMsg('Produto excluído.');
    carregar();
  }
});

function limparFormulario() {
  form.reset();
  form.elements.id.value = '';
  document.getElementById('form-titulo').textContent = 'Novo produto';
  document.getElementById('btn-salvar').textContent = 'Cadastrar';
  document.getElementById('btn-cancelar').hidden = true;
}

document.getElementById('btn-cancelar').addEventListener('click', () => {
  limparFormulario();
  mostrarMsg('');
});

document.getElementById('busca').addEventListener('input', carregar);

// ---------- TEMAS (os 5 estilos de CSS) ----------
const botoesTema = document.querySelectorAll('.temas button');

function aplicarTema(nome) {
  document.documentElement.dataset.tema = nome;
  localStorage.setItem('tema', nome);
  botoesTema.forEach((b) => b.classList.toggle('ativo', b.dataset.tema === nome));
}

botoesTema.forEach((b) => b.addEventListener('click', () => aplicarTema(b.dataset.tema)));
aplicarTema(localStorage.getItem('tema') || 'papel');

// ---------- começa aqui ----------
carregar();

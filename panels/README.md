# 📊 Painéis por Tipo de Cliente

Sistema organizado de dashboards para diferentes tipos de negócio no WordPress.

## 📁 Estrutura

```
panels/
├── base/                    ← Painel genérico (base para customizações)
├── ecommerce/               ← E-commerce (WooCommerce)
├── blog/                    ← Sites de conteúdo/blog
├── portfolio/               ← Portfólio/agências/profissionais
└── README.md                ← Você está aqui
```

## 🎯 Painéis Disponíveis

### 📦 E-commerce (WooCommerce)

**Arquivo:** `ecommerce/PanelWooCommerce-Final.html`

**Para quem:** Lojas virtuais, e-commerce, WooCommerce

**Seções:**
- Gestão de Produtos
- Gestão de Pedidos
- Gerenciamento do Site
- Ferramentas de Marketing

**URL GitHub Raw:**
```
https://raw.githubusercontent.com/ugprade/Panels/main/panels/ecommerce/PanelWooCommerce-Final.html
```

**Casos de uso:**
- Loja online de roupas
- Marketplace de produtos digitais
- E-commerce B2B
- Dropshipping

---

### 📝 Blog (Em desenvolvimento)

**Arquivo:** `blog/PanelBlog.html` _(em breve)_

**Para quem:** Sites de conteúdo, blogs, portais de notícias

**Seções (planejadas):**
- Gestão de Posts
- Gestão de Categorias
- Comentários
- SEO e Analytics

---

### 🎨 Portfolio (Em desenvolvimento)

**Arquivo:** `portfolio/PanelPortfolio.html` _(em breve)_

**Para quem:** Designers, fotógrafos, agências, freelancers

**Seções (planejadas):**
- Gestão de Projetos
- Galeria de Trabalhos
- Clientes
- Contatos e Leads

---

### 🔧 Base (Template Genérico)

**Arquivo:** `base/PanelBase.html` _(em breve)_

**Para quem:** Base neutra para qualquer tipo de site

**Seções (planejadas):**
- Gestão de Conteúdo
- Gerenciamento do Site
- Usuários
- Configurações

---

## 🚀 Como Usar

### 1. Escolha o Painel

Selecione o painel que melhor se encaixa no tipo de negócio do seu cliente:

- **E-commerce?** → `ecommerce/PanelWooCommerce-Final.html`
- **Blog?** → `blog/PanelBlog.html`
- **Portfolio?** → `portfolio/PanelPortfolio.html`
- **Outros?** → `base/PanelBase.html`

### 2. Pegue a URL Raw do GitHub

```
https://raw.githubusercontent.com/ugprade/Panels/main/panels/[TIPO]/[ARQUIVO].html
```

**Exemplo para E-commerce:**
```
https://raw.githubusercontent.com/ugprade/Panels/main/panels/ecommerce/PanelWooCommerce-Final.html
```

### 3. Configure no WordPress

**WordPress > Configurações > Dashboard GitHub**

```
HTML URL: https://raw.githubusercontent.com/ugprade/Panels/main/panels/ecommerce/PanelWooCommerce-Final.html

CSS 1: https://seusite.com/wp-content/uploads/bricks/css/global-variables.min.css
CSS 2: https://seusite.com/wp-content/uploads/bricks/css/color-palettes.min.css
CSS 3: [vazio ou custom]
```

## 🎨 Customização por Cliente

Todos os painéis suportam as mesmas variáveis CSS, permitindo:

### Modo 1: Standalone
- Não adicionar nenhum CSS
- Usa cores padrão do painel

### Modo 2: Bricks (Recomendado)
- CSS 1: `global-variables.min.css` (espaçamento, tipografia)
- CSS 2: `color-palettes.min.css` (cores do cliente)
- Resultado: Painel com identidade visual do cliente

### Modo 3: Totalmente Custom
- CSS 1 + 2: Bricks
- CSS 3: Customizações extras específicas

## 📋 Exemplos de Uso

### Cliente A: Loja de Roupas
```
Painel: ecommerce/PanelWooCommerce-Final.html
CSS 1: Bricks global
CSS 2: Bricks colors (rosa/roxo)
CSS 3: [vazio]
```

### Cliente B: Blog de Tecnologia
```
Painel: blog/PanelBlog.html
CSS 1: Bricks global
CSS 2: Bricks colors (azul/preto)
CSS 3: custom-tech.css (ícones específicos)
```

### Cliente C: Portfólio de Designer
```
Painel: portfolio/PanelPortfolio.html
CSS 1: Bricks global
CSS 2: Bricks colors (minimalista)
CSS 3: [vazio]
```

## 🔄 Workflow Multi-Cliente

### Estrutura Recomendada no GitHub

```bash
Panels/
├── panels/
│   ├── ecommerce/
│   │   └── PanelWooCommerce-Final.html
│   ├── blog/
│   │   └── PanelBlog.html
│   └── portfolio/
│       └── PanelPortfolio.html
│
└── customizacoes/                    # CSS customizado por cliente
    ├── cliente-a/
    │   └── custom.css
    ├── cliente-b/
    │   └── custom.css
    └── cliente-c/
        └── custom.css
```

### Configuração Típica

**1. Cliente com E-commerce + Bricks:**
```
HTML: panels/ecommerce/PanelWooCommerce-Final.html
CSS 1: Bricks global
CSS 2: Bricks colors
CSS 3: [vazio]
```

**2. Cliente com E-commerce + Custom:**
```
HTML: panels/ecommerce/PanelWooCommerce-Final.html
CSS 1: Bricks global
CSS 2: Bricks colors
CSS 3: customizacoes/cliente-a/custom.css
```

**3. Cliente standalone (sem Bricks):**
```
HTML: panels/ecommerce/PanelWooCommerce-Final.html
CSS 1, 2, 3: [vazio]
```

## 💡 Vantagens desta Estrutura

1. **Organização por tipo** - Fácil encontrar o painel certo
2. **URLs GitHub claras** - `/panels/ecommerce/...`
3. **Mesmo HTML base** - Um painel por tipo de negócio
4. **CSS em cascata** - Personalização sem duplicar HTML
5. **Escalável** - Adicione novos tipos facilmente

## 🛠️ Criando Novo Tipo de Painel

Se precisar criar um novo tipo:

1. **Crie a pasta:**
```bash
mkdir panels/nome-do-tipo
```

2. **Copie um painel base e adapte:**
```bash
cp panels/ecommerce/PanelWooCommerce-Final.html panels/nome-do-tipo/PanelNovoTipo.html
```

3. **Edite o conteúdo:**
- Mantenha a estrutura CSS
- Ajuste seções e cards
- Use mesmas classes CSS

4. **Documente aqui no README**

5. **Teste com CSS:**
- Standalone (sem CSS)
- Com Bricks
- Com customização

## 📚 Documentação

Para mais informações:

- **[../docs/GUIA-SISTEMA-FINAL.md](../docs/GUIA-SISTEMA-FINAL.md)** - Guia completo
- **[../docs/CUSTOMIZAR-CORES.md](../docs/CUSTOMIZAR-CORES.md)** - Paletas de cores
- **[../exemplo-custom-css.css](../exemplo-custom-css.css)** - Template CSS #3

---

**Sistema organizado por tipo = fácil escalar para múltiplos clientes!** 🚀

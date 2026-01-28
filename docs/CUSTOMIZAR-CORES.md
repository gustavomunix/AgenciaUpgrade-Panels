# 🎨 Como Customizar Cores por Cliente

Guia simples para personalizar o dashboard para cada cliente.

## 📍 Onde Editar

Abra o arquivo `PanelWooCommerce-Standalone.html` e localize (linha ~14):

```css
:root {
    --primary: #8338ec;           /* ← EDITE AQUI */
    --primary-dark: #692dbd;      /* ← EDITE AQUI */
    --primary-light: #ece1fc;     /* ← EDITE AQUI */
    --secondary: #ffbe0b;         /* ← EDITE AQUI */
    --secondary-dark: #cc9809;    /* ← EDITE AQUI */
    --base: #4d4d66;              /* ← EDITE AQUI */
    --base-dark: #2e2e3d;         /* ← EDITE AQUI */
    --base-light: #e4e4e8;        /* ← EDITE AQUI */
    --white: #ffffff;
    /* ... */
}
```

## 🎯 Exemplos de Paletas

### Cliente 1 - Azul Profissional

```css
:root {
    --primary: #2563eb;
    --primary-dark: #1e40af;
    --primary-light: #dbeafe;
    --secondary: #f59e0b;
    --secondary-dark: #d97706;
    --base: #334155;
    --base-dark: #1e293b;
    --base-light: #e2e8f0;
    --white: #ffffff;
}
```

**Preview:** Azul corporativo + Laranja para destaques

---

### Cliente 2 - Verde Minimalista

```css
:root {
    --primary: #10b981;
    --primary-dark: #059669;
    --primary-light: #d1fae5;
    --secondary: #fbbf24;
    --secondary-dark: #f59e0b;
    --base: #475569;
    --base-dark: #1e293b;
    --base-light: #e2e8f0;
    --white: #ffffff;
}
```

**Preview:** Verde natural + Amarelo dourado

---

### Cliente 3 - Roxo Moderno (Original)

```css
:root {
    --primary: #8338ec;
    --primary-dark: #692dbd;
    --primary-light: #ece1fc;
    --secondary: #ffbe0b;
    --secondary-dark: #cc9809;
    --base: #4d4d66;
    --base-dark: #2e2e3d;
    --base-light: #e4e4e8;
    --white: #ffffff;
}
```

**Preview:** Roxo vibrante + Amarelo (padrão)

---

### Cliente 4 - Vermelho Energético

```css
:root {
    --primary: #ef4444;
    --primary-dark: #dc2626;
    --primary-light: #fee2e2;
    --secondary: #3b82f6;
    --secondary-dark: #2563eb;
    --base: #3f3f46;
    --base-dark: #18181b;
    --base-light: #e4e4e7;
    --white: #ffffff;
}
```

**Preview:** Vermelho intenso + Azul complementar

---

### Cliente 5 - Rosa Criativo

```css
:root {
    --primary: #ec4899;
    --primary-dark: #db2777;
    --primary-light: #fce7f3;
    --secondary: #06b6d4;
    --secondary-dark: #0891b2;
    --base: #52525b;
    --base-dark: #27272a;
    --base-light: #e4e4e7;
    --white: #ffffff;
}
```

**Preview:** Rosa vibrante + Ciano

---

### Cliente 6 - Laranja Quente

```css
:root {
    --primary: #f97316;
    --primary-dark: #ea580c;
    --primary-light: #ffedd5;
    --secondary: #8b5cf6;
    --secondary-dark: #7c3aed;
    --base: #44403c;
    --base-dark: #1c1917;
    --base-light: #e7e5e4;
    --white: #ffffff;
}
```

**Preview:** Laranja caloroso + Roxo

---

## 🔧 Como Gerar Cores do Bricks

Se o cliente já tem Bricks configurado:

### 1. Encontre o CSS do Bricks

```
URL: https://seusite.com/wp-content/uploads/bricks/css/color-palettes.min.css
```

### 2. Abra no Navegador

Você verá algo como:

```css
:root {
    --bricks-color-primary: #8338ec;
    --bricks-color-secondary: #ffbe0b;
    /* ... */
}
```

### 3. Copie para o HTML

```css
/* No seu HTML standalone */
:root {
    --primary: #8338ec;        /* valor do --bricks-color-primary */
    --secondary: #ffbe0b;      /* valor do --bricks-color-secondary */
    /* ... */
}
```

---

## 🎨 Ferramentas para Escolher Cores

### Online - Gratuitas

1. **Coolors.co** - Gera paletas harmônicas
   ```
   https://coolors.co/
   ```

2. **Adobe Color** - Teoria das cores
   ```
   https://color.adobe.com/
   ```

3. **Tailwind Colors** - Paletas prontas
   ```
   https://tailwindcss.com/docs/customizing-colors
   ```

### A partir do Logo do Cliente

1. **Upload do logo em:**
   ```
   https://coolors.co/image-picker
   ```

2. Extrai as cores principais
3. Use como `--primary` e `--secondary`

---

## 📋 Checklist de Customização

Antes de fazer commit:

- [ ] Cores `--primary` e `--primary-dark` combinam?
- [ ] `--secondary` contrasta bem com primary?
- [ ] Testou no navegador localmente?
- [ ] Stats cards estão legíveis? (texto branco em fundo colorido)
- [ ] Badges ficaram bonitos?
- [ ] Hover nos cards ficou bom?

---

## 🚀 Workflow Completo

```bash
# 1. Clone/baixe o projeto
git clone https://github.com/seu-usuario/dashboards.git

# 2. Crie branch do cliente
git checkout -b cliente-abc

# 3. Abra PanelWooCommerce-Standalone.html

# 4. Edite as cores (linha 14)
:root {
    --primary: #NOVA_COR;
    /* ... */
}

# 5. Teste localmente (abra no navegador)

# 6. Commit
git add .
git commit -m "Cores Cliente ABC - Azul Profissional"
git push origin cliente-abc

# 7. Pegue URL raw
https://raw.githubusercontent.com/usuario/repo/cliente-abc/PanelWooCommerce-Standalone.html

# 8. Configure no WordPress do cliente
```

---

## 🎯 Dicas Profissionais

### 1. Mantenha Contraste

```css
/* BOM - Contraste alto */
--primary: #2563eb;        /* Azul escuro */
--primary-light: #dbeafe; /* Azul clarinho */

/* RUIM - Pouco contraste */
--primary: #60a5fa;        /* Azul médio */
--primary-light: #93c5fd;  /* Azul médio-claro */
```

### 2. Use Variações da Mesma Cor

```css
/* Pegue a cor primary */
--primary: #8338ec;

/* Gere dark (mais escuro ~20%) */
--primary-dark: #692dbd;

/* Gere light (mais claro ~80%) */
--primary-light: #ece1fc;
```

**Ferramenta:** https://www.0to255.com/8338ec

### 3. Teste Acessibilidade

```
https://webaim.org/resources/contrastchecker/

Teste:
- Texto branco em --primary
- Texto --base-dark em --primary-light
```

### 4. Documente as Cores

Crie um `CORES-CLIENTE.md`:

```markdown
# Cliente ABC - Paleta de Cores

- **Primary:** #2563eb (Azul Profissional)
- **Secondary:** #f59e0b (Laranja Destaque)
- **Base:** #334155 (Cinza Texto)

**Inspiração:** Logo do cliente
**Data:** 2024-01-28
**Aprovado por:** João Silva
```

---

## 📱 Preview Rápido

Quer ver como ficou antes de fazer commit?

### Método 1: Abrir Localmente

```bash
# Apenas abra o HTML no navegador
open PanelWooCommerce-Standalone.html

# Ou arraste para o navegador
```

### Método 2: Live Server (VSCode)

```bash
# 1. Instale extensão "Live Server"
# 2. Clique direito no HTML
# 3. "Open with Live Server"
# 4. Edite cores e veja mudanças em tempo real!
```

---

## 🎨 Galeria de Exemplos

Salvei alguns templates prontos:

```bash
exemplos/
├── azul-profissional.html
├── verde-natural.html
├── roxo-moderno.html
├── vermelho-energia.html
└── rosa-criativo.html
```

Use como base e customize!

---

**Pronto para criar dashboards únicos para cada cliente! 🎨**

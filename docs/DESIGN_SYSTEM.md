# Дизайн-система Yaremche

## 🎨 Філософія дизайну

**Сучасний мінімалізм з природними мотивами Карпат**

- Чистота та простота інтерфейсу
- Багато повітря (whitespace)
- Фокус на контенті та фотографіях
- Природна кольорова палітра
- Зрозумілість та зручність навігації

## 🎨 Кольорова палітра

### Primary Colors (Зелені тони - ліс)

```css
/* Основний зелений */
--primary-50:  #f0fdf4;   /* Дуже світлий */
--primary-100: #dcfce7;
--primary-200: #bbf7d0;
--primary-300: #86efac;
--primary-400: #4ade80;
--primary-500: #22c55e;   /* Основний */
--primary-600: #16a34a;   /* Темніший */
--primary-700: #15803d;
--primary-800: #166534;
--primary-900: #14532d;   /* Найтемніший */
```

**Використання:**
- CTA кнопки: `primary-600`
- Hover стани: `primary-700`
- Акценти: `primary-500`
- Легкі фони: `primary-50`

### Secondary Colors (Коричневі тони - гори, дерево)

```css
/* Коричневі/земляні */
--secondary-50:  #fafaf9;
--secondary-100: #f5f5f4;
--secondary-200: #e7e5e4;
--secondary-300: #d6d3d1;
--secondary-400: #a8a29e;
--secondary-500: #78716c;   /* Основний */
--secondary-600: #57534e;   /* Темніший */
--secondary-700: #44403c;
--secondary-800: #292524;
--secondary-900: #1c1917;
```

**Використання:**
- Заголовки: `secondary-900`
- Основний текст: `secondary-700`
- Вторинний текст: `secondary-500`
- Borders: `secondary-200`

### Neutral Colors (Сірі)

```css
--gray-50:  #fafaf9;
--gray-100: #f5f5f4;
--gray-200: #e7e5e4;
--gray-500: #78716c;
--gray-700: #44403c;
--gray-900: #1c1917;
```

### Accent Colors

```css
/* Блакитний (небо, вода) */
--accent-blue: #3b82f6;

/* Помаранчевий (заходи сонця) */
--accent-orange: #f97316;

/* Червоний (важливі дії) */
--danger: #ef4444;

/* Жовтий (попередження, featured) */
--warning: #eab308;

/* Білий/чорний */
--white: #ffffff;
--black: #0a0a0a;
```

## 📝 Типографіка

### Font Stack

```css
font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI',
             'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
```

**Альтернативи:**
- **Montserrat** - для заголовків (більш geometric)
- **Lato** - універсальний
- **Open Sans** - дуже читабельний

### Font Sizes

```css
/* Tailwind scale */
text-xs:   12px  (0.75rem)
text-sm:   14px  (0.875rem)
text-base: 16px  (1rem)      /* Основний текст */
text-lg:   18px  (1.125rem)
text-xl:   20px  (1.25rem)
text-2xl:  24px  (1.5rem)
text-3xl:  30px  (1.875rem)  /* H3 */
text-4xl:  36px  (2.25rem)   /* H2 */
text-5xl:  48px  (3rem)      /* H1 */
text-6xl:  60px  (3.75rem)   /* Hero titles */
```

### Font Weights

```css
font-light:     300
font-normal:    400  /* Body text */
font-medium:    500  /* Emphasis */
font-semibold:  600  /* Subheadings */
font-bold:      700  /* Headings */
font-extrabold: 800  /* Hero titles */
```

### Line Heights

```css
leading-tight:  1.25  /* Заголовки */
leading-normal: 1.5   /* Основний текст */
leading-relaxed: 1.75 /* Великі блоки тексту */
```

## 🧱 Компоненти

### Buttons

#### Primary Button
```jsx
className="bg-primary-600 hover:bg-primary-700
           text-white font-medium
           px-6 py-3 rounded-lg
           transition-colors duration-200
           shadow-sm hover:shadow-md"
```

#### Secondary Button
```jsx
className="bg-white border-2 border-secondary-300
           hover:border-secondary-400
           text-secondary-700 font-medium
           px-6 py-3 rounded-lg
           transition-all duration-200"
```

#### Ghost Button
```jsx
className="text-secondary-700 hover:text-primary-600
           font-medium px-4 py-2
           transition-colors duration-200"
```

### Cards

```jsx
className="bg-white rounded-lg
           border border-secondary-100
           shadow-sm hover:shadow-lg
           transition-shadow duration-300
           overflow-hidden"
```

### Inputs

```jsx
className="w-full px-4 py-3
           border border-secondary-200
           rounded-lg
           focus:outline-none focus:ring-2
           focus:ring-primary-500 focus:border-transparent
           text-secondary-700
           transition-all duration-200"
```

### Badges/Tags

```jsx
// Neutral
className="inline-block px-3 py-1
           bg-secondary-100 text-secondary-700
           text-sm font-medium rounded-full"

// Featured
className="inline-block px-3 py-1
           bg-warning text-white
           text-sm font-semibold rounded-full"
```

## 🖼️ Layout & Spacing

### Container

```jsx
className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
```

### Spacing Scale (Tailwind)

```css
/* Використовуємо 8px grid system */
p-2:  8px
p-4:  16px  /* Base unit */
p-6:  24px
p-8:  32px
p-12: 48px
p-16: 64px
p-20: 80px
p-24: 96px
```

### Grid System

```jsx
// 2 колонки на mobile, 3 на desktop
className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"

// Sidebar + content
className="grid grid-cols-1 lg:grid-cols-4 gap-8"
```

## 🎭 Ефекти та Анімації

### Shadows

```css
/* Картки */
shadow-sm:  0 1px 2px rgba(0,0,0,0.05)
shadow:     0 1px 3px rgba(0,0,0,0.1)
shadow-md:  0 4px 6px rgba(0,0,0,0.1)
shadow-lg:  0 10px 15px rgba(0,0,0,0.1)
shadow-xl:  0 20px 25px rgba(0,0,0,0.1)
```

### Transitions

```css
/* Базова transition */
transition-all duration-200

/* Для shadows */
transition-shadow duration-300

/* Для кольорів */
transition-colors duration-200
```

### Hover Effects

```jsx
// Card hover
className="transform hover:-translate-y-1
           transition-transform duration-200"

// Button hover
className="hover:scale-105 transition-transform duration-200"
```

## 🌄 Hero Sections

### Gradient Background

```jsx
className="bg-gradient-to-br from-primary-600 via-primary-700 to-secondary-700
           text-white"
```

### Overlay Pattern

```jsx
// Для фото у background
className="relative"
// Child overlay:
className="absolute inset-0 bg-black/40"
```

## 📱 Responsive Breakpoints

```css
/* Tailwind defaults */
sm:  640px   /* Mobile landscape */
md:  768px   /* Tablet */
lg:  1024px  /* Desktop */
xl:  1280px  /* Large desktop */
2xl: 1536px  /* Extra large */
```

## 🖼️ Imagery

### Image Treatment

- **Aspect Ratios**: 16:9 для hero, 4:3 для карток, 1:1 для аватарів
- **Border Radius**: `rounded-lg` (8px) для більшості, `rounded-xl` для великих елементів
- **Filters**: Легкий blur для background images
- **Overlay**: 40% темний overlay для текста поверх зображень

### Placeholder Images

Використовувати сірий фон з іконкою:
```jsx
className="bg-secondary-100 flex items-center justify-center"
```

## ♿ Accessibility

### Contrast Ratios

- Основний текст на білому: `text-secondary-700` (AAA)
- Кнопки: `bg-primary-600` + `text-white` (AAA)
- Посилання: `text-primary-600` підкреслені

### Focus States

```css
focus:outline-none
focus:ring-2
focus:ring-primary-500
focus:ring-offset-2
```

## 📐 Приклад Layout

### Homepage Hero

```jsx
<section className="relative bg-gradient-to-br from-primary-600 to-secondary-700 text-white py-20 lg:py-32">
  <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div className="max-w-3xl">
      <h1 className="text-5xl lg:text-6xl font-bold mb-6">
        Ласкаво просимо до Яремче
      </h1>
      <p className="text-xl lg:text-2xl text-primary-50 mb-8">
        Відкрийте для себе красу Карпат
      </p>
      <button className="bg-white text-primary-700 hover:bg-primary-50
                         px-8 py-4 rounded-lg font-semibold
                         shadow-lg hover:shadow-xl
                         transform hover:scale-105
                         transition-all duration-200">
        Почати подорож
      </button>
    </div>
  </div>
</section>
```

### Place Card

```jsx
<div className="bg-white rounded-lg border border-secondary-100
                shadow-sm hover:shadow-lg
                transition-all duration-300
                overflow-hidden group">
  <div className="relative h-48 overflow-hidden">
    <img
      src={image}
      alt={name}
      className="w-full h-full object-cover
                 group-hover:scale-110 transition-transform duration-300"
    />
    {featured && (
      <span className="absolute top-3 right-3
                       bg-warning text-white
                       px-3 py-1 rounded-full
                       text-sm font-semibold shadow-md">
        Популярне
      </span>
    )}
  </div>
  <div className="p-5">
    <h3 className="text-xl font-semibold text-secondary-900 mb-2">
      {name}
    </h3>
    <p className="text-secondary-600 text-sm mb-3">
      {address}
    </p>
    <div className="flex items-center justify-between">
      <span className="text-secondary-500">💰💰</span>
      <span className="text-primary-600 font-medium">
        Детальніше →
      </span>
    </div>
  </div>
</div>
```

## 🎨 Tailwind Config

```js
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0fdf4',
          // ... rest
          600: '#16a34a',
          700: '#15803d',
        },
        secondary: {
          // ... browns/grays
        }
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
}
```

---

**Version**: 1.0
**Last Updated**: 7 лютого 2025

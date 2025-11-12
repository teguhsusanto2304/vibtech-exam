<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Vibtech Genesis Examination Portal</title>

  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      -webkit-text-size-adjust: 100%;
    }

    :root {
      --color-primary: #0A2342;
    }

    body {
      margin: 0;
      padding: 0;
      width: 100%;
      min-height: 100vh;
      overflow-x: hidden;
    }

    .peer:checked ~ .peer-checked\:bg-primary {
      background-color: var(--color-primary) !important;
    }

    .peer:checked ~ .peer-checked\:border-primary {
      border-color: var(--color-primary) !important;
    }

    .peer:checked ~ .peer-checked\:text-white {
      color: white !important;
    }

    /* Fix label wrapping and spacing */
    label.flex {
      align-items: flex-start;
    }

    /* Force proper width inside flexbox layouts */
    .layout-content-container,
    main,
    form {
      width: 100%;
      max-width: 960px;
      min-width: 0;
    }

    /* Edge flex fix for shrinkable content */
    .option-label-content {
      min-width: 0;
      flex: 1 1 auto;
      word-wrap: break-word;
    }

    /* Slightly tighter radiobutton labels */
    input[type="radio"] {
      transform: scale(1.1);
    }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display">
  <div class="flex flex-col min-h-screen w-full">
    <div class="flex flex-1 justify-center py-5">
      <div class="layout-content-container flex flex-col bg-white dark:bg-gray-900 shadow-md rounded-lg overflow-hidden">
        <!-- HEADER -->
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-gray-700 px-6 sm:px-10 py-3 bg-white dark:bg-background-dark">
          <div class="flex items-center gap-4 text-gray-800 dark:text-white">
            <span class="material-symbols-outlined text-primary text-3xl">waves</span>
            <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Vibtech Genesis Examination Portal</h2>
          </div>
        </header>
        @yield('content')
      </div>
    </div>
  </div>
 </body>
</html>
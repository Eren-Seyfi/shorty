<!doctype html>
<html lang="tr">

<head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>@yield('title', config('app.name'))</title>

      <style>
            :root {
                  --bg: #0b1020;
                  --card: rgba(255, 255, 255, .06);
                  --border: rgba(255, 255, 255, .12);
                  --text: rgba(255, 255, 255, .92);
                  --muted: rgba(255, 255, 255, .68);
                  --primary: #16c784;
                  --warning: #f6c343;
                  --danger: #fb7185;
            }

            * {
                  box-sizing: border-box
            }

            body {
                  margin: 0;
                  min-height: 100vh;
                  font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial;
                  color: var(--text);
                  background:
                        radial-gradient(1000px 500px at 15% 10%, rgba(22, 199, 132, .20), transparent 60%),
                        radial-gradient(1000px 500px at 85% 12%, rgba(0, 212, 255, .18), transparent 60%),
                        radial-gradient(900px 450px at 50% 90%, rgba(246, 195, 67, .14), transparent 60%),
                        var(--bg);
            }

            .wrap {
                  min-height: 100vh;
                  display: grid;
                  place-items: center;
                  padding: 28px;
            }

            .card {
                  width: min(760px, 100%);
                  border: 1px solid var(--border);
                  background: var(--card);
                  backdrop-filter: blur(10px);
                  border-radius: 20px;
                  padding: 28px;
                  box-shadow: 0 12px 40px rgba(0, 0, 0, .35);
            }

            .top {
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                  gap: 12px;
                  margin-bottom: 18px;
            }

            .brand {
                  display: flex;
                  align-items: center;
                  gap: 10px;
                  font-weight: 700;
            }

            .dot {
                  width: 12px;
                  height: 12px;
                  border-radius: 999px;
                  background: var(--primary);
                  box-shadow: 0 0 0 6px rgba(22, 199, 132, .16);
            }

            .badge {
                  display: inline-flex;
                  align-items: center;
                  font-size: 12px;
                  padding: 6px 10px;
                  border-radius: 999px;
                  border: 1px solid var(--border);
                  color: var(--muted);
            }

            h1 {
                  margin: 0 0 10px 0;
                  font-size: 30px;
                  line-height: 1.15;
            }

            p {
                  margin: 0 0 18px 0;
                  color: var(--muted);
                  line-height: 1.6;
            }

            .actions {
                  display: flex;
                  flex-wrap: wrap;
                  gap: 10px;
                  margin-top: 12px;
            }

            a.btn {
                  display: inline-flex;
                  align-items: center;
                  justify-content: center;
                  height: 40px;
                  padding: 0 14px;
                  border-radius: 12px;
                  text-decoration: none;
                  border: 1px solid var(--border);
                  color: var(--text);
                  background: rgba(255, 255, 255, .06);
            }

            a.btn.primary {
                  border-color: rgba(22, 199, 132, .45);
                  background: rgba(22, 199, 132, .15);
            }

            .hint {
                  margin-top: 14px;
                  font-size: 12px;
                  color: rgba(255, 255, 255, .55);
            }

            .icon {
                  width: 48px;
                  height: 48px;
                  border-radius: 16px;
                  display: grid;
                  place-items: center;
                  border: 1px solid var(--border);
                  background: rgba(255, 255, 255, .06);
                  margin-bottom: 14px;
            }
      </style>
</head>

<body>
      <div class="wrap">
            <div class="card">
                  <div class="top">
                        <div class="brand">
                              <span class="dot"></span>
                              <span>{{ config('app.name') }}</span>
                        </div>

                        @yield('badge')
                  </div>

                  @yield('content')
            </div>
      </div>
</body>

</html>
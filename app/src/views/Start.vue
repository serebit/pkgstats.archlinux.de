<template>
  <main class="container" role="main">
    <div class="row">
      <div class="col-12 col-xl-9">
        <div class="mb-4">
          <h1>Arch Linux package statistics</h1>
          <p>Contributing is as easy as installing the package - a weekly cron job will take care of the
            rest. You will be sending us a list of packages installed on your system, along with the
            architecture and mirror you use. This information is anonymous and cannot be used to identify
            you, but it will help us prioritize our efforts and make Arch even better.</p>
        </div>

        <div>
          <h4>Getting started</h4>
          <ol class="ps-4">
            <li>Install pkgstats by <code>pacman -Syu <strong>pkgstats</strong></code></li>
            <li>Ensure the weekly timer is started: <code>systemctl start
              <strong>pkgstats.timer</strong></code></li>
            <li>Check the timer status with <code>systemctl status <strong>pkgstats.timer</strong></code>
            </li>
            <li>For additional help see <code>pkgstats <strong>help</strong></code></li>
          </ol>

          <h4>Searching for packages</h4>
          <p>The cli client can be used to search for packages and show the most popular matches:</p>
          <pre class="text-light bg-dark p-2"><code>$ pkgstats <strong>search</strong> firefox
firefox                    63.53
firefox-developer-edition  6.30
firefox-i18n-de            3.32
firefox-i18n-en-us         2.65
firefox-ublock-origin      2.55
firefox-adblock-plus       2.07
firefox-i18n-fr            1.61
firefox-i18n-en-gb         1.52
firefox-nightly            1.35
firefox-i18n-ru            1.22

10 of 320 results

See more results at <a class="code-link" href="https://pkgstats.archlinux.de/packages#query=firefox">https://pkgstats.archlinux.de/packages#query=firefox</a></code></pre>

          <h4>Comparing package statistics</h4>
          <p>The popularity of one or more packages can be queried:</p>
          <pre
            class="text-light bg-dark p-2"><code>$ pkgstats <strong>show</strong> firefox chromium epiphany konqueror
firefox        63.53
chromium       48.80
epiphany       16.33
konqueror      10.62

See more results at <a class="code-link"
                       href="https://pkgstats.archlinux.de/compare/packages#packages=chromium,epiphany,firefox,konqueror">https://pkgstats.archlinux.de/compare/packages#packages=chromium,epiphany,firefox,konqueror</a></code></pre>

          <h4>Submitting your package data</h4>
          <p>While the systemd timer automatically submits your data, you may want to inspect the data that are being
            sent:</p>
          <pre class="text-light bg-dark p-2"><code>$ pkgstats <strong>submit --dump-json</strong>
{
  "version": "3",
  "system": {
    "architecture": "x86_64_v3"
  },
  "os": {
    "architecture": "x86_64"
  },
  "pacman": {
    "mirror": "https://repos.archlinux.org/",
    "packages": [
      "a52dec",
      ...
      "zziplib"
    ]
  }
}</code></pre>
          <p>If you are just curious about the CPU architecture that pkgstats detects, simply run:</p>
          <pre class="text-light bg-dark p-2"><code>$ pkgstats <strong>arch cpu</strong>
x86_64_v3</code></pre>
        </div>
      </div>

      <div class="col-12 col-xl-3">
        <h4>Dokumentation</h4>
        <ul class="ps-4">
          <li>See <code>pkgstats help</code></li>
          <li><a href="https://wiki.archlinux.org/index.php/Pkgstats" rel="nofollow">Arch Linux Wiki</a></li>
          <li>
            <router-link :to="{name: 'api-doc'}">pkgstats server API documentation</router-link>
          </li>
        </ul>

        <h4>Development</h4>
        <ul class="ps-4">
          <li><a href="https://github.com/archlinux-de/pkgstats-cli" rel="nofollow">
            pkgstats cli</a></li>
          <li><a href="https://github.com/archlinux-de/pkgstats.archlinux.de" rel="nofollow">
            pkgstats server</a></li>
        </ul>

        <h4>Announcements</h4>
        <ul class="ps-4">
          <li>
            <a href="https://www.archlinux.org/news/pkgstats-contribution-was-never-that-easy/"
               rel="nofollow">
              pkgstats: contribution was never that easy</a>
          </li>
          <li>
            <a href="https://www.archlinux.org/news/pkgstats-round-two-take-your-vote-and-help-improving-arch/"
               rel="nofollow">
              pkgstats round two: take your vote and help improving Arch</a>
          </li>
          <li>
            <a href="https://www.archlinux.org/news/pkgstats-now-collects-modules-usage/" rel="nofollow">
              pkgstats now collects modules usage </a>
          </li>
        </ul>

        <h4>Explore</h4>
        <ul class="ps-4">
          <li>Browse the
            <router-link :to="{name: 'packages'}">package statistics</router-link>
            and lookup the popularity of packages.
          </li>
          <li>See some
            <router-link :to="{name: 'fun'}">fun statistics</router-link>
            and see which packages are the most popular in their category.
          </li>
          <li>Use the
            <router-link :to="{name: 'api-doc'}">API</router-link>
            to write your own clients
          </li>
        </ul>
      </div>
    </div>
  </main>
</template>

<style lang="scss">
.code-link {
  color: inherit;

  &:hover {
    color: inherit;
  }
}
</style>

<script setup>
import { useHead } from '@vueuse/head'

useHead({ title: 'Arch Linux package statistics' })
</script>

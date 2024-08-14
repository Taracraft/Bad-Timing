<!-- Fußzeile -->
<footer class="footer">
    <div class="footer-content">
        <p>© <a href="https://www.bad-timing.eu">https://www.bad-timing.eu</a></p>
        <p>Seitenladezeit: <span id="load-time"></span> ms</p>
    </div>
</footer>

<script>
    // Berechne die Ladezeit
    window.onload = function() {
        var loadTime = window.performance.timing.domContentLoadedEventEnd - window.performance.timing.navigationStart;
        document.getElementById('load-time').textContent = loadTime;
    };
</script>
</body>
</html>
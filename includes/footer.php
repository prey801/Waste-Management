<?php
// includes/footer.php
?>
<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> EcoTrack Waste Management. All rights reserved.</p>
        <p>Developed by <a href="https://www.linkedin.com/in/june-mutiso/" target="_blank">June Mutiso</a> for Zetech University</p>
    </div>
</footer>

<style>
    footer {
        background: linear-gradient(to right, var(--primary-dark), var(--primary));
        color: white;
        padding: 20px 0;
        width: 100%;
        position: relative;
        z-index: 2;
    }

    footer .container {
        max-width: 1200px;
        margin: 0 auto;
        text-align: center;
    }

    footer p {
        margin: 5px 0;
        font-size: 0.9rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    footer p a {
        color: var(--accent);
        text-decoration: none;
        transition: var(--transition);
    }

    footer p a:hover {
        color: var(--primary-light);
        text-decoration: underline;
    }

    @media (max-width: 576px) {
        footer {
            padding: 15px 0;
        }

        footer p {
            font-size: 0.8rem;
        }
    }
</style>

<script src="/assets/js/main.js"></script>
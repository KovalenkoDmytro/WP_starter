<link rel="stylesheet" href="<?php echo get_theme_file_uri('/spray-in-box-liner-page2026/CompareServices/style.css?v=1.2'); ?>">

<section class="comparison-section">
    <div class="section-text-container">
        <h2 class="section-headline">Why Go Spray-In Over Drop-In?</h2>
        <p class="section-text">
            Drop-in liners are the go-to for most truck owners, but popularity isn't always a sign of quality. When considering long-term value, see how the two options truly compare.
        </p>
    </div>

    <div class="comparison-toggle">
        <button class="toggle-btn active active-drop-in-btn" data-tab="drop-in">Drop-In Liner</button>
        <button class="toggle-btn active-spray-in-btn" data-tab="spray-in">Spray-In Liner</button>
    </div>

    <div class="comparison-table active-drop-in">
        <div class="table-header-desktop">
            <div class="header-cell header-drop-in">Drop-In Liner</div>
            <div class="header-cell header-spray-in">Spray-In Liner</div>
        </div>

        <div class="table-row">
            <div class="row-label">Fit</div>
            <div class="row-content">
                <div class="cell col-drop-in">Sits on top of the box, generic sizing</div>
                <div class="cell col-spray-in">Custom-fit, follows every contour</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Moisture</div>
            <div class="row-content">
                <div class="cell col-drop-in">Traps water between liner and bed</div>
                <div class="cell col-spray-in">Airtight seal prevents any moisture</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Rust</div>
            <div class="row-content">
                <div class="cell col-drop-in">Causes vibration-wear and rust</div>
                <div class="cell col-spray-in">Prevents it at the surface</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Shifting</div>
            <div class="row-content">
                <div class="cell col-drop-in">Moves under heavy loads, scratches the box</div>
                <div class="cell col-spray-in">Permanent, goes nowhere</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Box space</div>
            <div class="row-content">
                <div class="cell col-drop-in">Reduces usable space by sitting on top</div>
                <div class="cell col-spray-in">Conforms to the box, you keep every inch</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Cargo grip</div>
            <div class="row-content">
                <div class="cell col-drop-in">Smooth plastic, loads slide</div>
                <div class="cell col-spray-in">Textured non-slip finish</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Weight</div>
            <div class="row-content">
                <div class="cell col-drop-in">Adds bulk</div>
                <div class="cell col-spray-in">Lighter, no added mass</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Calgary winters</div>
            <div class="row-content">
                <div class="cell col-drop-in">Salt and chemicals get trapped underneath</div>
                <div class="cell col-spray-in">Sealed against road chemicals and temperature swings</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Cost</div>
            <div class="row-content">
                <div class="cell col-drop-in">Cheaper upfront, more damage long-term</div>
                <div class="cell col-spray-in">One install, long-term protection</div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const table = document.querySelector('.comparison-table');
        const buttons = document.querySelectorAll('.toggle-btn');

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const tabName = button.getAttribute('data-tab');

                // 1. Manage Button Active States
                buttons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                // 2. Manage Table Column Visibility
                table.classList.remove('active-drop-in', 'active-spray-in');
                if (tabName === 'drop-in') {
                    table.classList.add('active-drop-in');
                } else {
                    table.classList.add('active-spray-in');
                }
            });
        });
    });
</script>
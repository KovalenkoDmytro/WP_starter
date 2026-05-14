

<section class="comparison-section">
    <div class="section-text-container">
        <h2 class="section-headline">PPF vs. Ceramic Coating</h2>
        <p class="section-text">
            PPF and ceramic coating solve different problems. Many Calgary drivers benefit from using both, depending on how and where they drive.
        </p>
    </div>

    <div class="comparison-toggle">
        <button class="toggle-btn active active-drop-in-btn" data-tab="drop-in">Paint Protection Film</button>
        <button class="toggle-btn active-spray-in-btn" data-tab="spray-in">Ceramic Coating</button>
    </div>

    <div class="comparison-table active-drop-in">
        <div class="table-header-desktop">
            <div class="header-cell header-drop-in">Paint Protection Film</div>
            <div class="header-cell header-spray-in">Ceramic Coating</div>
        </div>

        <div class="table-row">
            <div class="row-label">What it protects against</div>
            <div class="row-content">
                <div class="cell col-drop-in">Rock chips, road debris, physical impact</div>
                <div class="cell col-spray-in">UV, chemicals, dirt, water spots</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">How it works</div>
            <div class="row-content">
                <div class="cell col-drop-in">Physical barrier film applied on top of paint</div>
                <div class="cell col-spray-in">Bonds chemically to the paint surface</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Self-healing</div>
            <div class="row-content">
                <div class="cell col-drop-in">Yes, minor surface marks fade with heat</div>
                <div class="cell col-spray-in">No</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Gloss enhancement</div>
            <div class="row-content">
                <div class="cell col-drop-in">Minimal</div>
                <div class="cell col-spray-in">Yes</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Best for</div>
            <div class="row-content">
                <div class="cell col-drop-in">High-impact zones like hoods, bumpers, mirrors</div>
                <div class="cell col-spray-in">Easier maintenance, UV and chemical resistance</div>
            </div>
        </div>

        <div class="table-row">
            <div class="row-label">Can they be combined?</div>
            <div class="row-content">
                <div class="cell col-drop-in">Yes. PPF underneath, ceramic on top</div>
                <div class="cell col-spray-in">Yes. a common setup for full protection</div>
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
document.addEventListener('DOMContentLoaded', function () {

    const toggleListBtn = document.getElementById('toggleListBtn');
    const closeListBtn = document.getElementById('closeListBtn');
    const tableContainer = document.getElementById('tableContainer');
    const overviewContainer = document.getElementById('overviewContainer');
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');

    // Show List
    function showList(category = null) {

        tableContainer.style.display = 'block';

        toggleListBtn.innerHTML =
            '<i class="fas fa-eye-slash"></i> Hide List';

        toggleListBtn.classList.add('active');

        const rows = document.querySelectorAll('.assets-table tbody tr');

        rows.forEach(row => {

            if (category === null) {
                row.style.display = '';
            } else {

                if (row.dataset.category === category) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }

            }

        });

        setTimeout(() => {
            tableContainer.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }, 100);
    }

    // Hide List
    function hideList() {

        tableContainer.style.display = 'none';

        toggleListBtn.innerHTML =
            '<i class="fas fa-list"></i> Show List';

        toggleListBtn.classList.remove('active');

    }

    // Toggle Button
    toggleListBtn.addEventListener('click', function () {

        if (window.getComputedStyle(tableContainer).display === 'none') {

            // Show ALL assets
            showList();

        } else {

            hideList();

        }

    });

    // Close Button
    if (closeListBtn) {

        closeListBtn.addEventListener('click', function () {

            hideList();

            overviewContainer.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

        });

    }

    // Search
    if (searchForm) {

        searchForm.addEventListener('submit', function () {

            if (searchInput.value.trim() !== '') {

                setTimeout(() => {

                    showList();

                }, 100);

            }

        });

    }

});

document.querySelectorAll('.viewAssetBtn').forEach(button => {

    button.addEventListener('click', function () {

        const id = this.dataset.id;

        const modal = new bootstrap.Modal(document.getElementById('assetModal'));

        modal.show();

        document.getElementById('assetModalBody').innerHTML =
        `
            <div class="text-center p-5">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
            </div>
        `;

        fetch('/assets/' + id + '/details')

        .then(response => response.json())

        .then(asset => {

            document.getElementById('assetModalBody').innerHTML = `

            <table class="table table-bordered">

                <tr>
                    <th width="35%">Asset Tag</th>
                    <td>${asset.asset_tag}</td>
                </tr>

                <tr>
                    <th>Category</th>
                    <td>${asset.category ?? ''}</td>
                </tr>

                <tr>
                    <th>Brand</th>
                    <td>${asset.brand ?? ''}</td>
                </tr>

                <tr>
                    <th>Provider</th>
                    <td>${asset.provider ?? ''}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>${asset.status ?? ''}</td>
                </tr>

                <tr>
                    <th>Company</th>
                    <td>${asset.company ?? ''}</td>
                </tr>

                <tr>
                    <th>Delivery Date</th>
                    <td>${asset.delivery_date ?? ''}</td>
                </tr>

                <tr>
                    <th>Specification</th>
                    <td>${asset.specification ?? ''}</td>
                </tr>

                <tr>
                    <th>Remarks</th>
                    <td>${asset.remarks ?? ''}</td>
                </tr>

            </table>

            `;

        });

    });

});
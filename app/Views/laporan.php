<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="row mb-4">
                <!-- PDF Form -->
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Generate PDF</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url('home/generate_pdf') ?>" method="post">
                                <div class="mb-3">
                                    <label for="start_date_pdf" class="form-label">Start Date:</label>
                                    <input type="date" id="start_date_pdf" name="start_date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="end_date_pdf" class="form-label">End Date:</label>
                                    <input type="date" id="end_date_pdf" name="end_date" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Generate PDF</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Excel Form -->
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Generate Excel</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url('home/generate_excel') ?>" method="post">
                                <div class="mb-3">
                                    <label for="start_date_excel" class="form-label">Start Date:</label>
                                    <input type="date" id="start_date_excel" name="start_date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="end_date_excel" class="form-label">End Date:</label>
                                    <input type="date" id="end_date_excel" name="end_date" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Generate Excel</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Windows Form -->
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Generate Windows</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url('home/generate_window_result') ?>" method="post">
                                <div class="mb-3">
                                    <label for="start_date_windows" class="form-label">Start Date:</label>
                                    <input type="date" id="start_date_windows" name="start_date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="end_date_windows" class="form-label">End Date:</label>
                                    <input type="date" id="end_date_windows" name="end_date" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Generate Windows</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

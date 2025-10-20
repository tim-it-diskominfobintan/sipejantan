<div class="modal fade" id="modal-update_cron_expression" tabindex="-1" aria-labelledby="modal-update-cron_expressionLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-update_cron_expression">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-update-cron_expressionLabel">Ubah jadwal periodik cron</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   <input type="text" name="key" id="update_cron_expression-key" class="form-control" hidden>
                   <input type="text" name="command" id="update_cron_expression-command" class="form-control" hidden>
                   <input type="text" name="artisan_command" id="update_cron_expression-artisan_command" class="form-control" hidden>

                    <div class="form-group mb-3">
                        <label for="update_cron_expression-template_expression_laravel">Template expression
                            (laravel)</label>
                        <select name="template_expression_laravel"
                            id="update_cron_expression-template_expression_laravel" class="form-select">
                            <option value="* * * * *">Setiap menit (everyMinute)</option>
                            <option value="*/5 * * * *">Setiap 5 menit (everyFiveMinutes)</option>
                            <option value="*/10 * * * *">Setiap 10 menit (everyTenMinutes)</option>
                            <option value="*/15 * * * *">Setiap 15 menit (everyFifteenMinutes)</option>
                            <option value="0 * * * *">Setiap jam (hourly)</option>
                            <option value="0 8,13,18 * * *">Setiap jam sibuk (hourlyAt - 8,13,18)</option>
                            <option value="0 0 * * *">Setiap hari (daily)</option>
                            <option value="0 0 * * *">Tengah malam (dailyAt('00:00'))</option>
                            <option value="0 1 * * *">Setiap hari jam 1 pagi (dailyAt)</option>
                            <option value="0 0 * * 0">Setiap minggu (weekly)</option>
                            <option value="0 0 * * 1">Setiap Senin (weeklyOn)</option>
                            <option value="0 0 1 * *">Setiap awal bulan (monthly)</option>
                            <option value="0 0 1 1 *">Setiap awal tahun (yearly)</option>
                            <option value="">Custom (atur manual)</option>
                        </select>
                        <div id="update_cron_expression-template_expression_laravel-msg"></div>
                    </div>
                    <div class="form-group">
                        <label for="update_cron_expression-cron">Expression</label>
                        <input type="text" name="cron" id="update_cron_expression-cron" class="form-control" readonly>
                        <div id="update_cron_expression-cron-msg"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

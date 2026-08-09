<?php if (!empty($contracts)): ?>
    <?php foreach ($contracts as $contract): ?>
        <?php 
            $warningBadge = '';
            $rowStyle = 'border-bottom: 1px solid var(--border-color);';
            
            // Logic cảnh báo hết hạn (3 tháng = 90 ngày, 2 tháng = 60 ngày, 1 tháng = 30 ngày)
            if ($contract['status_code'] === 'active') {
                $days = (int)$contract['days_remaining'];
                if ($days <= 30) {
                    $warningBadge = '<span class="status status-red" style="font-weight: 600;">1 Tháng</span>';
                    $rowStyle = 'border-bottom: 1px solid var(--border-color); background-color: rgba(211, 47, 47, 0.05); border-left: 3px solid var(--red);';
                } elseif ($days <= 60) {
                    $warningBadge = '<span class="status status-orange" style="font-weight: 600; color: #e65100; background-color: #ffe0b2;">2 Tháng</span>';
                } elseif ($days <= 90) {
                    $warningBadge = '<span class="status status-yellow" style="font-weight: 600; color: #f57f17; background-color: #fffde7;">3 Tháng</span>';
                }
            }

            // Đếm số lượng phụ lục từ CSDL
            $db = database::getInstance();
            $appCountRes = $db->selectOne("SELECT COUNT(*) as count FROM contract_appendices WHERE appendix_contract_id = :id", ['id' => $contract['contract_id']]);
            $appCount = $appCountRes['count'] ?? 0;
        ?>
        <tr style="<?php echo $rowStyle; ?>">
            <td class="cell-mono" style="padding: 14px 16px; font-weight: 600; color: var(--text-heading);">
                <?php echo htmlspecialchars($contract['contract_number']); ?>
            </td>
            <td style="padding: 14px 16px; font-weight: 500;">
                <?php echo htmlspecialchars($contract['contract_name']); ?>
            </td>
            <td style="padding: 14px 16px; font-weight: 600; color: var(--text-heading);">
                <?php echo htmlspecialchars($contract['trader_name']); ?><br>
                <small style="color: var(--text-muted); font-size: 11px; font-weight: normal;"><?php echo htmlspecialchars($contract['trader_phone']); ?></small>
            </td>
            <td class="cell-mono" style="padding: 14px 16px; font-weight: 600;">
                <?php echo htmlspecialchars($contract['stall_code']); ?>
            </td>
            <td style="padding: 14px 16px; color: var(--text-muted);">
                <?php echo !empty($contract['contract_sign_date']) ? date('d/m/Y', strtotime($contract['contract_sign_date'])) : date('d/m/Y', strtotime($contract['contract_start_date'])); ?>
            </td>
            <td style="padding: 14px 16px; color: <?php echo ($contract['status_code'] === 'expired') ? 'var(--red)' : 'var(--text-muted)'; ?>; font-weight: <?php echo ($contract['status_code'] === 'expired') ? '600' : 'normal'; ?>;">
                <?php echo date('d/m/Y', strtotime($contract['contract_end_date'])); ?>
            </td>
            <td style="padding: 14px 16px; text-align: center;">
                <?php echo $warningBadge ?: '<span style="color: var(--text-muted); font-size: 11px;">-</span>'; ?>
            </td>
            <td style="padding: 14px 16px; font-weight: 600; color: var(--primary);">
                <?php 
                $areaSize = (float)($contract['stall_area_size'] ?? 0);
                $unitPrice = (float)($contract['price'] ?? 0);
                $monthlyRent = $areaSize > 0 ? round($unitPrice * $areaSize) : $unitPrice;
                echo number_format($monthlyRent, 0, ',', '.'); 
                ?> đ
                <?php if ($areaSize > 0): ?>
                    <br><small style="color: var(--text-muted); font-size: 11px; font-weight: normal;"><?php echo number_format($unitPrice, 0, ',', '.'); ?> đ/m²</small>
                <?php endif; ?>
            </td>
            <td style="padding: 14px 16px; color: var(--text-muted);">
                <?php echo number_format($contract['contract_deposit'], 0, ',', '.'); ?> đ
            </td>
            <td style="padding: 14px 16px; text-align: center;">
                <?php 
                if (!empty($contract['contract_file'])): 
                    $files = [];
                    $fileStr = $contract['contract_file'];
                    if (substr($fileStr, 0, 1) === '[' && substr($fileStr, -1) === ']') {
                        $files = json_decode($fileStr, true) ?: [];
                    } else {
                        $files = [$fileStr];
                    }
                    
                    if (!empty($files)):
                        echo '<div style="display: flex; justify-content: center; gap: 8px; flex-wrap: wrap;">';
                        foreach ($files as $index => $file):
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $icon = 'fa-file-image';
                            $color = 'var(--primary)';
                            if ($ext === 'pdf') {
                                $icon = 'fa-file-pdf';
                                $color = 'var(--red)';
                            } elseif ($ext === 'doc' || $ext === 'docx') {
                                $icon = 'fa-file-word';
                                $color = '#2b579a';
                            }
                            ?>
                            <a href="<?php global $upload_path; echo $upload_path . '/contracts/' . htmlspecialchars($file); ?>" target="_blank" style="color: <?php echo $color; ?>; font-size: 15px; display: inline-flex;" title="Tải tệp đính kèm <?php echo $index + 1; ?> (.<?php echo strtoupper($ext); ?>)">
                                <i class="fa-solid <?php echo $icon; ?>"></i>
                            </a>
                            <?php 
                        endforeach;
                        echo '</div>';
                    endif;
                else: 
                    ?>
                    <span style="color: var(--text-muted); font-size: 11px;">N/A</span>
                <?php endif; ?>
            </td>
            <td style="padding: 14px 16px; text-align: center;">
                <button class="btn btn-ghost btn-sm" onclick="App.contract.viewAppendices(<?php echo $contract['contract_id']; ?>, '<?php echo htmlspecialchars($contract['contract_number']); ?>')" style="padding: 4px; color: var(--primary); display: inline-flex; align-items: center; gap: 4px;" title="Xem phụ lục hợp đồng">
                    <i class="fa-solid fa-paperclip"></i> (<?php echo $appCount; ?>)
                </button>
            </td>
            <td style="padding: 14px 16px;">
                <span class="status <?php echo htmlspecialchars($contract['color_class'] ?: 'status-gray'); ?>">
                    <?php echo htmlspecialchars($contract['status_name']); ?>
                </span>
            </td>
            <td style="padding: 14px 16px; text-align: right;">
                <div style="display: flex; justify-content: flex-end; gap: 4px;">
                    <!-- Kích hoạt hợp đồng (Chỉ dành cho hợp đồng Khởi tạo) -->
                    <?php if ($contract['status_code'] === 'draft'): ?>
                        <button class="btn btn-outline btn-sm" onclick="App.contract.activateContract(<?php echo $contract['contract_id']; ?>, '<?php echo htmlspecialchars($contract['contract_number']); ?>', '<?php echo htmlspecialchars($contract['contract_sign_date'] ?? ''); ?>', '<?php echo $contract['contract_start_date']; ?>', '<?php echo $contract['contract_end_date']; ?>', <?php echo (float)$contract['contract_deposit']; ?>, '<?php echo htmlspecialchars($contract['contract_file'] ?? '', ENT_QUOTES, 'UTF-8'); ?>')" style="padding: 4px 6px; color: var(--green); border-color: var(--green);" title="Kích hoạt hợp đồng">
                            <i class="fa-solid fa-circle-check"></i> Kích hoạt
                        </button>
                    <?php endif; ?>

                    <!-- Gia hạn / Thanh lý / Chấm dứt (Dành cho hợp đồng Hoạt động hoặc Hết hạn) -->
                    <?php if ($contract['status_code'] === 'active' || $contract['status_code'] === 'expired'): ?>
                        <button class="btn btn-outline btn-sm" onclick="App.contract.renewContract(<?php echo $contract['contract_id']; ?>, '<?php echo htmlspecialchars($contract['contract_number']); ?>', '<?php echo $contract['contract_end_date']; ?>')" style="padding: 4px 6px; color: var(--primary);" title="Gia hạn">
                            <i class="fa-solid fa-calendar-plus"></i>
                        </button>
                        <button class="btn btn-outline btn-sm" onclick="App.contract.liquidateContract(<?php echo $contract['contract_id']; ?>, '<?php echo htmlspecialchars($contract['contract_number']); ?>')" style="padding: 4px 6px; color: var(--orange);" title="Thanh lý">
                            <i class="fa-solid fa-file-contract"></i>
                        </button>
                        <button class="btn btn-outline btn-sm" onclick="App.contract.terminateContract(<?php echo $contract['contract_id']; ?>, '<?php echo htmlspecialchars($contract['contract_number']); ?>')" style="padding: 4px 6px; color: var(--red);" title="Chấm dứt trước hạn">
                            <i class="fa-solid fa-ban"></i>
                        </button>
                    <?php endif; ?>

                    <!-- Tái kích hoạt (Dành cho hợp đồng Thanh lý, Chấm dứt trước hạn hoặc Hết hạn) -->
                    <?php if ($contract['status_code'] === 'liquidated' || $contract['status_code'] === 'terminated' || $contract['status_code'] === 'expired'): ?>
                        <button class="btn btn-outline btn-sm" onclick="App.contract.reactivateContract(<?php echo $contract['contract_id']; ?>, '<?php echo htmlspecialchars($contract['contract_number']); ?>')" style="padding: 4px 6px; color: var(--green); border-color: var(--green);" title="Tái kích hoạt hợp đồng">
                            <i class="fa-solid fa-rotate-left"></i> Tái kích hoạt
                        </button>
                    <?php endif; ?>

                    <!-- Sửa, Lịch sử, In hợp đồng (Chỉ hiển thị nếu không phải trạng thái Khởi tạo) -->
                    <?php if ($contract['status_code'] !== 'draft'): ?>
                        <!-- Sửa hợp đồng -->
                        <?php if ($contract['status_code'] !== 'expired' && $contract['status_code'] !== 'liquidated' && $contract['status_code'] !== 'terminated'): ?>
                        <button class="btn btn-outline btn-sm" onclick="App.contract.editContract(<?php echo $contract['contract_id']; ?>, '<?php echo htmlspecialchars($contract['contract_number'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($contract['contract_name'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($contract['contract_sign_date'] ?? ''); ?>', '<?php echo $contract['contract_start_date']; ?>', '<?php echo $contract['contract_end_date']; ?>', <?php echo (float)$contract['contract_deposit']; ?>, '<?php echo htmlspecialchars($contract['contract_description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($contract['contract_file'] ?? '', ENT_QUOTES, 'UTF-8'); ?>')" style="padding: 4px 6px; color: var(--primary);" title="Sửa hợp đồng">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <?php endif; ?>

                        <!-- Lịch sử hợp đồng -->
                        <button class="btn btn-outline btn-sm" onclick="App.contract.viewHistory(<?php echo $contract['contract_id']; ?>, '<?php echo htmlspecialchars($contract['contract_number']); ?>')" style="padding: 4px 6px; color: var(--primary);" title="Xem lịch sử chỉnh sửa">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </button>

                        <!-- In hợp đồng -->
                        <a href="<?php echo BASE_URL; ?>admin/contract_print/<?php echo $contract['contract_id']; ?>" target="_blank" class="btn btn-outline btn-sm" style="padding: 4px 6px; color: var(--primary); display: inline-flex; align-items: center;" title="In hợp đồng theo mẫu">
                            <i class="fa-solid fa-print"></i>
                        </a>

                        <!-- Xuất file Word (.docx) -->
                        <a href="<?php echo BASE_URL; ?>admin/contract_export_docx/<?php echo $contract['contract_id']; ?>" class="btn btn-outline btn-sm" style="padding: 4px 6px; color: #2b579a; display: inline-flex; align-items: center;" title="Xuất file Word (.docx)">
                            <i class="fa-solid fa-file-word"></i>
                        </a>
                    <?php endif; ?>

                    <!-- Xóa mềm (Mọi trạng thái đều có thể xóa/ẩn đi) -->
                    <button class="btn btn-outline btn-sm" onclick="App.contract.deleteContract(<?php echo $contract['contract_id']; ?>, '<?php echo htmlspecialchars($contract['contract_number']); ?>')" style="padding: 4px 6px; color: var(--text-muted);" title="Xóa mềm">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="13" style="padding: 30px; text-align: center; color: var(--text-muted);">Không có dữ liệu hợp đồng.</td>
    </tr>
<?php endif; ?>

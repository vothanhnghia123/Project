<?php
$pageTitle = 'Quản lý người dùng';
$myId = (int)($_SESSION['IDNguoiDung'] ?? 0);
?>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <h3><i class="fa-solid fa-users" style="color:#e74c3c;margin-right:6px;"></i> Danh sách người dùng</h3>
        <div style="display:flex; align-items:center; gap:12px;">
            <span style="font-size:13px; color:#888;"><?php echo count($users); ?> tài khoản</span>
            <div style="position:relative;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#aaa; font-size:13px;"></i>
                <input type="text" id="search-nd" oninput="searchUser()"
                       placeholder="Tìm tên, email..."
                       style="padding:7px 10px 7px 30px; border:1px solid #ddd; border-radius:8px; font-size:13px; outline:none; width:200px;">
            </div>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="admin-tbl" id="nd-table">
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Điện thoại</th>
                    <th>Địa chỉ</th>
                    <th style="text-align:center; width:100px;">Vai trò</th>
                    <th style="text-align:center; width:130px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:#aaa;">
                            Chưa có người dùng nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                    <?php $uid = (int)$u['IDNguoiDung']; $isAdmin = ((int)$u['IDVaiTro'] === 1); ?>
                    <tr>
                        <td><span style="color:#e74c3c; font-weight:700;">#<?php echo $uid; ?></span></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:9px;">
                                <div style="width:34px; height:34px; border-radius:50%;
                                    background:<?php echo $isAdmin ? 'linear-gradient(135deg,#e74c3c,#c0392b)' : 'linear-gradient(135deg,#3498db,#2980b9)'; ?>;
                                    display:flex; align-items:center; justify-content:center;
                                    color:#fff; font-size:14px; font-weight:700; flex-shrink:0;">
                                    <?php echo mb_strtoupper(mb_substr($u['HoTen'], 0, 1)); ?>
                                </div>
                                <div>
                                    <div style="font-weight:600; color:#1a1f2e;"><?php echo htmlspecialchars($u['HoTen']); ?></div>
                                    <?php if ($uid === $myId): ?>
                                        <div style="font-size:11px; color:#e74c3c;">(Tài khoản của bạn)</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px; color:#666;"><?php echo htmlspecialchars($u['Email']); ?></td>
                        <td style="font-size:13px; color:#666;">
                            <?php echo !empty($u['DienThoai']) ? htmlspecialchars($u['DienThoai']) : '<em style="color:#ccc;">—</em>'; ?>
                        </td>
                        <td style="font-size:13px; color:#666; max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            <?php echo !empty($u['DiaChi']) ? htmlspecialchars($u['DiaChi']) : '<em style="color:#ccc;">—</em>'; ?>
                        </td>
                        <td style="text-align:center;" id="role-<?php echo $uid; ?>">
                            <?php if ($isAdmin): ?>
                                <span style="background:#fee2e2; color:#991b1b; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:700;">
                                    <i class="fa-solid fa-shield-halved"></i> Admin
                                </span>
                            <?php else: ?>
                                <span style="background:#dbeafe; color:#1e40af; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:700;">
                                    <i class="fa-solid fa-user"></i> User
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:center;" id="action-<?php echo $uid; ?>">
                            <?php if (!$isAdmin): ?>
                                <button onclick="capQuyen(<?php echo $uid; ?>)"
                                        style="background:#27ae60; color:#fff; border:none; padding:5px 12px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer;">
                                    <i class="fa-solid fa-shield-halved"></i> Cấp Admin
                                </button>
                            <?php elseif ($uid !== $myId): ?>
                                <button onclick="haQuyen(<?php echo $uid; ?>)"
                                        style="background:#f39c12; color:#fff; border:none; padding:5px 12px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer;">
                                    <i class="fa-solid fa-arrow-down"></i> Hạ quyền
                                </button>
                            <?php else: ?>
                                <span style="font-size:12px; color:#aaa;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function capQuyen(id) {
    if (!confirm('Cấp quyền Admin cho người dùng #' + id + '?')) return;
    fetch('index.php?controller=AdminNguoidung&action=capquyen&param=' + id)
        .then(r => r.text())
        .then(() => {
            document.getElementById('role-' + id).innerHTML =
                '<span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;"><i class="fa-solid fa-shield-halved"></i> Admin</span>';
            document.getElementById('action-' + id).innerHTML =
                '<button onclick="haQuyen(' + id + ')" style="background:#f39c12;color:#fff;border:none;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;"><i class="fa-solid fa-arrow-down"></i> Hạ quyền</button>';
        });
}

function haQuyen(id) {
    if (!confirm('Hạ quyền người dùng #' + id + ' về User?')) return;
    fetch('index.php?controller=AdminNguoidung&action=haquyen&param=' + id)
        .then(r => r.text())
        .then(data => {
            if (data.trim() === 'self') { alert('Không thể hạ quyền tài khoản của chính mình!'); return; }
            document.getElementById('role-' + id).innerHTML =
                '<span style="background:#dbeafe;color:#1e40af;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:700;"><i class="fa-solid fa-user"></i> User</span>';
            document.getElementById('action-' + id).innerHTML =
                '<button onclick="capQuyen(' + id + ')" style="background:#27ae60;color:#fff;border:none;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;"><i class="fa-solid fa-shield-halved"></i> Cấp Admin</button>';
        });
}

function searchUser() {
    const q = document.getElementById('search-nd').value.toLowerCase();
    document.querySelectorAll('#nd-table tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>

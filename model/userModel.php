<?php
/**
 * Model quản lý tài khoản người dùng (Nhân viên BQL, Admin)
 */
class userModel {
    private $db;

    public function __construct() {
        $this->db = database::getInstance();
    }

    /**
     * Lấy danh sách tất cả người dùng
     */
    public function getAll() {
        $sql = "SELECT user_id, user_username AS username, user_fullname AS fullname, user_email AS email, user_group, user_is_active AS is_active, user_created_at AS created_at 
                FROM users 
                WHERE user_is_active = 1 
                ORDER BY user_id DESC";
        return $this->db->select($sql);
    }

    /**
     * Lấy người dùng theo ID
     */
    public function getById($id) {
        $sql = "SELECT user_id, user_username AS username, user_fullname AS fullname, user_email AS email, user_group, user_is_active AS is_active 
                FROM users 
                WHERE user_id = :id";
        return $this->db->selectOne($sql, ['id' => $id]);
    }

    /**
     * Lấy người dùng theo tên đăng nhập
     */
    public function getByUsername($username) {
        $sql = "SELECT u.*, u.user_password AS password, u.user_is_active AS is_active, sa.actor_code 
                FROM users u 
                LEFT JOIN system_actors sa ON u.user_actor_id = sa.actor_id 
                WHERE u.user_username = :username";
        return $this->db->selectOne($sql, ['username' => $username]);
    }

    /**
     * Lấy người dùng theo email
     */
    public function getByEmail($email) {
        $sql = "SELECT *, user_id AS id, user_email AS email FROM users WHERE user_email = :email";
        return $this->db->selectOne($sql, ['email' => $email]);
    }

    /**
     * Kiểm tra thông tin đăng nhập và trả về thông tin user
     */
    public function authenticate($username, $password) {
        $user = $this->getByUsername($username);
        if ($user && password_verify($password, $user['user_password'])) {
            if ($user['user_is_active'] == 1) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Thêm tài khoản người dùng mới
     */
    public function create($data) {
        $sql = "INSERT INTO users (user_username, user_password, user_fullname, user_email, user_group, user_actor_id, user_is_active) 
                VALUES (:username, :password, :fullname, :email, :user_group, :actor_id, :is_active)";
        
        $params = [
            'username'   => $data['username'],
            'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
            'fullname'   => $data['fullname'],
            'email'      => $data['email'] ?? null,
            'user_group' => $data['user_group'] ?? 2,
            'actor_id'   => $data['actor_id'],
            'is_active'  => $data['is_active'] ?? 1
        ];

        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Cập nhật thông tin tài khoản người dùng
     */
    public function update($id, $data) {
        $sql = "UPDATE users 
                SET user_fullname = :fullname, user_email = :email, user_group = :user_group, user_actor_id = :actor_id, user_is_active = :is_active 
                WHERE user_id = :id";
        
        $params = [
            'id'         => $id,
            'fullname'   => $data['fullname'],
            'email'      => $data['email'] ?? null,
            'user_group' => $data['user_group'] ?? 2,
            'actor_id'   => $data['actor_id'],
            'is_active'  => $data['is_active'] ?? 1
        ];

        return $this->db->query($sql, $params);
    }

    /**
     * Vô hiệu hóa tài khoản người dùng (Soft delete)
     */
    public function delete($id) {
        $sql = "UPDATE users SET user_is_active = 0 WHERE user_id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }

    /**
     * Cập nhật mật khẩu tài khoản
     */
    public function updatePassword($id, $newPassword) {
        $sql = "UPDATE users SET user_password = :password WHERE user_id = :id";
        return $this->db->query($sql, [
            'id'       => $id,
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }

    /**
     * Gán token khôi phục mật khẩu
     */
    public function setResetToken($email, $token, $expires) {
        $sql = "UPDATE users SET user_reset_token = :token, user_reset_expires_at = :expires WHERE user_email = :email AND user_is_active = 1";
        return $this->db->query($sql, [
            'email'   => $email,
            'token'   => $token,
            'expires' => $expires
        ]);
    }

    /**
     * Lấy user theo reset token hợp lệ
     */
    public function getByResetToken($token) {
        $sql = "SELECT *, user_id AS id FROM users WHERE user_reset_token = :token AND user_reset_expires_at > NOW() AND user_is_active = 1";
        return $this->db->selectOne($sql, ['token' => $token]);
    }

    /**
     * Xóa token khôi phục mật khẩu
     */
    public function clearResetToken($id) {
        $sql = "UPDATE users SET user_reset_token = NULL, user_reset_expires_at = NULL WHERE user_id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }
}

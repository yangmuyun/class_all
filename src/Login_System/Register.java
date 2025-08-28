package Login_System;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.SQLException;

import javax.swing.JOptionPane;

public class Register {
    String name;
    String ID;
    String password;
    String confirmPassword;

    private String driver = "com.mysql.cj.jdbc.Driver";
    private String url = "jdbc:mysql://localhost:3306/hotelsql?serverTimezone=UTC&characterEncoding=utf-8";
    private String user = "root";
    private String sqlPassword = "12481632";

    void setName(String name) {
        this.name = name;
    }
    void setID(String ID) {
        this.ID = ID;
    }
    void setPassword(String password) {
        this.password = password;
    }
    void setConfirmPasswd(String confirmPassword) {
        this.confirmPassword = confirmPassword;

    }


    //判断注册的账号是否符合规则
    boolean JudgeRegister() throws SQLException, ClassNotFoundException {

        if(this.name.equals("")) {
            JOptionPane.showMessageDialog(null, "用户名不能为空！", "用户名", JOptionPane.ERROR_MESSAGE);
            return false;
        }

        if(this.ID.equals("")) {
            JOptionPane.showMessageDialog(null, "账号不能为空！", "账号为空", JOptionPane.ERROR_MESSAGE);
            return false;
        }

        if(this.password.equals("")) {
            JOptionPane.showMessageDialog(null, "密码不能为空！", "密码为空", JOptionPane.ERROR_MESSAGE);
            return false;
        }

        if(!this.password.equals(this.confirmPassword)) {
            JOptionPane.showMessageDialog(null, "两次输入的密码不一致!", "密码不一致", JOptionPane.ERROR_MESSAGE);
            return false;
        }

        //符合规则，弹出注册成功的窗口，并将账号添加数据库
        JOptionPane.showMessageDialog(null, "注册成功");
        addAdmin();
        return true;
    }

    //向数据库添加Admin账户
    void addAdmin() throws ClassNotFoundException, SQLException {
        String sql="insert into admin (id, name, password) values (?,?,?)";
        Class.forName(driver);
        try {
            Connection conn = DriverManager.getConnection(url, user, sqlPassword);
            PreparedStatement ps = conn.prepareStatement(sql);
            ps.setString(1, this.ID);
            ps.setString(2, this.name);
            ps.setString(3, this.password);
            ps.executeUpdate();
            ps.close();
            conn.close();

        }catch(SQLException ex) {
            //System.out.println("添加用户失败！");
            ex.printStackTrace();
        }

    }
}
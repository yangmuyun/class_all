package Login_System;

import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.sql.SQLException;

import javax.swing.*;

/*
 * 管理员注册界面
 *
 */
public class AdminRegister extends JFrame{
    AdminRegister () {
        init();
    }
    void init() {
        JPanel panel=new JPanel(){
            @Override
            protected void paintComponent( Graphics g){
                super.paintComponent(g);
                Image image=new ImageIcon("src/png/登录界面.jpg").getImage();
                g.drawImage(image,0,0,getWidth(),getHeight(),this);
            }
        };
        setContentPane(panel);
        setTitle("用户注册");
        setLayout(null);

        JLabel nameStr = new JLabel("用户名:");
        nameStr.setBounds(250, 150, 100, 25);
        nameStr.setOpaque(true);
        add(nameStr);

        JLabel IDStr = new JLabel("账号:");
        IDStr.setBounds(250, 200, 100, 25);
        IDStr.setOpaque(true);
        add(IDStr);

        JLabel passwordStr = new JLabel("密码:");
        passwordStr.setBounds(250, 250, 100, 25);
        passwordStr.setOpaque(true);
        add(passwordStr);

        JLabel confirmStr = new JLabel("确认密码:");
        confirmStr.setBounds(250, 300, 100, 25);
        confirmStr.setOpaque(true);
        add(confirmStr);

        JTextField userName = new JTextField();
        userName.setBounds(320, 150, 150, 25);
        add(userName);

        JTextField userID = new JTextField();
        userID.setBounds(320, 200, 150, 25);
        add(userID);

        JPasswordField password = new JPasswordField();
        password.setBounds(320, 250, 150, 25);
        add(password);

        JPasswordField confirmPassword = new JPasswordField();
        confirmPassword.setBounds(320, 300, 150, 25);
        add(confirmPassword);

        JButton buttonRegister = new JButton("注册");
        buttonRegister.setBounds(350, 350, 70, 25);
        add(buttonRegister);



        setBounds(400, 100, 800, 600);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setVisible(true);
        //为注册按钮增加监听器
        buttonRegister.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                String name = userName.getText();
                String ID = userID.getText();
                String passwd = new String (password.getPassword());
                String confirmPasswd = new String (confirmPassword.getPassword());

                //创建Register类
                Register register = new Register();
                register.setID(ID);
                register.setName(name);
                register.setPassword(passwd);
                register.setConfirmPasswd(confirmPasswd);

                //如果注册成功，返回登录界面
                try {
                    if(register.JudgeRegister()) {

                        setVisible(false);
                        new Login_Register();
                    }
                } catch (SQLException | ClassNotFoundException e1) {
                    e1.printStackTrace();
                }

            }

        });
    }
}

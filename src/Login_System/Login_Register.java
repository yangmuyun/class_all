package Login_System;/*
 * 登录注册界面
 *
 */
import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;


public class Login_Register extends JFrame{
    Login_Register() {
        init();
    }
    //登录界面初始化
    public void init() {
        JPanel panel=new JPanel(){
            @Override
            protected void paintComponent( Graphics g){
                super.paintComponent(g);
                Image image=new ImageIcon("src/png/登录界面.jpg").getImage();
                g.drawImage(image,0,0,getWidth(),getHeight(),this);
            }
        };
        setContentPane(panel);
        setTitle("登录");
        setLayout(null);

        JLabel nameStr = new JLabel("账号:");
        nameStr.setBounds(250, 200, 100, 25);
        nameStr.setOpaque(true);
        add(nameStr);

        JLabel passwordStr = new JLabel("密码:");
        passwordStr.setBounds(250, 250, 100, 25);
        passwordStr.setOpaque(true);
        add(passwordStr);

        JTextField userID = new JTextField();
        userID.setBounds(300, 200, 150, 25);
        add(userID);

        JPasswordField password = new JPasswordField();
        password.setBounds(300, 250, 150, 25);
        add(password);

        JButton buttonLogin = new JButton("登录");
        buttonLogin.setBounds(275, 300, 70, 25);
        add(buttonLogin);

        JButton buttonRegister = new JButton("注册");
        buttonRegister.setBounds(375, 300, 70, 25);
        add(buttonRegister);

        setBounds(400, 100, 800, 600);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);


        setVisible(true);
        //为登录按钮添加监听器
        buttonLogin.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                String ID = userID.getText();
                String passwd = new String (password.getPassword());

                //创建一个Admin用户，把输入框中的用户名密码和提出来
                Admin admin = new Admin();
                admin.setID(ID);
                admin.setPassword(passwd);

                //登录
                Login login = new Login();
                login.setAdmin(admin);

                if(login.JudgeAdmin()==0) {
                    //弹出账号或密码错误的窗口
                    JOptionPane.showMessageDialog(null, "账号或密码错误", "账号或密码错误", JOptionPane.WARNING_MESSAGE);
                    //清除密码框中的信息
                    password.setText("");
                    //清除账号框中的信息
                    userID.setText("");

                    //System.out.println("登陆失败");
                } else {
                    //弹出登录成功的窗口
                   // JOptionPane.showMessageDialog(null, "登陆成功", "登陆成功", JOptionPane.INFORMATION_MESSAGE);
                    //点击确定后会跳转到主窗口
                    dispose();
                    setVisible(false);


                }

            }
        });

        //为注册按钮添加监听器
        buttonRegister.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                //注册页面
                setVisible(false);
                new AdminRegister();
            }
        });
    }

    public static void main(String []args) {
        //主程序
        //登录窗口
         new Login_Register();
    }
}
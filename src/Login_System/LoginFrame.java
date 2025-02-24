//package Login_System;
//
//import javax.swing.*;
//import java.awt.*;
//import java.awt.event.*;
//public class LoginFrame extends JFrame implements ActionListener{
//      private JTextField usernameField;
//      private JPasswordField passwordField;
//      private JButton loginButton;
//      private JButton registerButton;
//      public LoginFrame() {
//          setTitle("斗地主登陆");
//          setSize(800,600);
//          setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
//           JPanel panel1 = new JPanel();
//           JPanel panel2=new JPanel();
//           JPanel panel=new JPanel(){
//               @Override
//               protected void paintComponent( Graphics g){
//                   super.paintComponent(g);
//                   Image image=new ImageIcon("src/png/登录界面.jpg").getImage();
//                   g.drawImage(image,0,0,getWidth(),getHeight(),this);
//               }
//           };
//           panel1.setLayout(new GridLayout(2,1));                // 创建用户名标签和文本框
//           JLabel usernameLabel = new JLabel("用户名:");
//           JLabel passwordLabel = new JLabel("密码:");
//           panel1.add(usernameLabel);
//           panel1.add(passwordLabel);
//
//           panel2.setLayout(new GridLayout(2,1));
//           usernameField = new JTextField(15);
//           passwordField = new JPasswordField(15);
//           panel2.add(usernameField);
//           panel2.add(passwordField);
//           // 创建登陆按钮
//
//           loginButton = new JButton("登陆");
//           loginButton.addActionListener(this);
//           registerButton=new JButton("注册");
//           registerButton.addActionListener(this);
//
//            //希望两个面板大小分布是40%和60%（label和text）
//           setContentPane(panel);
//           JPanel panel4=new JPanel();
//           panel4.setLayout(new GridLayout(1,2));
//           panel4.add(panel1);
//           panel4.add(panel2);
//
//           setLayout(null);
//           panel4.setBounds(this.getWidth()/2-140,this.getHeight()/2-70,300,100);
//           loginButton.setBounds(this.getWidth()/2-130,this.getHeight()/2+50,100,50);
//           registerButton.setBounds(this.getWidth()/2+50,this.getHeight()/2+50,100,50);
//           add(loginButton);
//           add(registerButton);
//           add(panel4);
//           setVisible(true);
//      }
//      public void actionPerformed(ActionEvent e) {        // 处理登陆按钮点击事件
//           String username = usernameField.getText();
//           String password = new String(passwordField.getPassword());
//           //判断用户名密码是否正确，这里需要修改
//           if (username.equals("admin") && password.equals("admin")) {
//              // JOptionPane.showMessageDialog(this, "登陆成功！");
//               dispose();            // 启动游戏主界面
//               //new GameMain();
//           } else {
//               JOptionPane.showMessageDialog(this, "用户名或密码错误！");
//           }
//      }
//      public static void main(String[] args) {
//          new LoginFrame();
//      }
//}
//

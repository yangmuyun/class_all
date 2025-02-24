package Login_System;/*
 * 游戏开始界面
 *
 */
import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

public class Start extends JFrame implements ActionListener {
    private JButton b;
    Start(){
        initComponents();
        pack();

    }
    public void initComponents() {
        JPanel panel=new JPanel(){
            @Override
            protected void paintComponent( Graphics g){
                super.paintComponent(g);
                Image image=new ImageIcon("src/png/start.jpg").getImage();
                g.drawImage(image,0,0,getWidth(),getHeight(),this);
            }
        };


        b = new JButton("开始游戏");
        b.setBounds(350, 350, 130, 40);
        b.setBackground(Color.ORANGE);
        b.addActionListener(this);

        JFrame fr = new JFrame("开始");
        fr.setContentPane(panel);
        fr.setLayout(null);
        fr.add(b);
        fr.setBounds(400, 100, 800, 500);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        fr.setVisible(true);



    }

    public void actionPerformed(ActionEvent e){
        if(e.getSource()==b){
            this.dispose();
            new Login_Register();
        }
    }
  public static void main(String[] args){
    new Start();
  }
}
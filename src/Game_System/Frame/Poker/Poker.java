package Game_System.Frame.Poker;

import javax.swing.*;
import Game_System.Frame.GameFrame;

import java.awt.*;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;

public class Poker extends JLabel implements MouseListener {
    GameFrame gameFrame;
    //牌的名字，例如1-1 第一个代表花色，第二个代表大小
    String name;
    //牌展示正面还是反面
    boolean up;
    //是否可点击
    boolean canClick = false;

    public boolean isClicked() {
        return clicked;
    }

    //是否已经被点击 false-未点击
    boolean clicked = false;

    public Poker(GameFrame m, String name, boolean up) {
        this.gameFrame = m;
        this.name = name;
        this.up = up;
        this.canClick = false;
        //判断当前的牌是显示正面还是背面,判断后设置poker的图片
        if (this.up) {
            this.turnFront();
        } else {
            this.turnRear();
        }
        //设置牌的宽高大小
        this.setSize(73, 96);
        //把牌显示出来
        this.setVisible(true);
        //给每一张牌添加鼠标监听
        this.addMouseListener(this);
    }

    @Override
    public String getName() {
        return name;
    }

    //显示正面
    public void turnFront() {
        this.setIcon(new ImageIcon("image/poker2/" + name + ".png"));
        this.up = true;
    }

    //显示背面
    public void turnRear() {
        this.setIcon(new ImageIcon("image/poker2/rear.png"));
        this.up = false;
    }
    //在抢完地主之后 设置可以被点击
    public void setCanClick(boolean canClick) {
        this.canClick = canClick;
    }

    @Override
    public void mouseClicked(MouseEvent e) {
        //可以被点击的话
        if(canClick){
            Point nowP = getLocation();
            int x = nowP.x;
            int y = nowP.y;
            //已经被点击了 y+20
            if(clicked){
                y+=20;
                clicked = false;
            }else{
                y-=20;
                clicked = true;
            }
            Point newP = new Point(x,y);
            setLocation(newP);
        }
    }

    @Override
    public void mousePressed(MouseEvent e) {

    }

    @Override
    public void mouseReleased(MouseEvent e) {

    }

    @Override
    public void mouseEntered(MouseEvent e) {

    }

    @Override
    public void mouseExited(MouseEvent e) {

    }

    public static int getColor(Poker poker){
        return Integer.parseInt(poker.getName().substring(0,1));
    }
    //A-14 2-15 小王-16 大王-17
    public static int getValue(Poker poker){
        int i = Integer.parseInt(poker.getName().substring(2));
        if (poker.getName().substring(2).equals("2"))
            i += 13;
        if (poker.getName().substring(2).equals("1"))
            i += 13;
        if (Poker.getColor(poker) == 5)
            i += 2;
        return i;
    }
}

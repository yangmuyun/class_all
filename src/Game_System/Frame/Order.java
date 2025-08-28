package Game_System.Frame;

import Game_System.Frame.Poker.Poker;

import java.awt.*;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;

public class Order {
    public static void order(ArrayList<Poker> list){

        Collections.sort(list, new Comparator<Poker>() {
            @Override
            public int compare(Poker o1,Poker o2) {
                //得到花色和大小
                String[] a1 = o1.getName().split("-");
                String[] a2 = o2.getName().split("-");
                //1-黑桃 2-红桃 3-梅花 4-方块 5-大小王
                int color1 = Integer.parseInt(a1[0]);
                int color2 = Integer.parseInt(a2[0]);
                //A-20 2-30 小王-101 大王-102
                int number1 = Integer.parseInt(a1[1]);
                int number2 = Integer.parseInt(a2[1]);

                //重新计算牌的值
                //是王的话
                if(color1 == 5){
                    number1+=100;
                }
                if(color2 == 5){
                    number2+=100;
                }
                //A的话
                if(number1==1){
                    number1=20;
                }
                if(number2==1){
                    number2=20;
                }
                //2的话
                if(number1==2){
                    number1=30;
                }
                if(number2==2){
                    number2=30;
                }
                //排序
                if(number1 == number2){
                    return color2-color1;
                }else{
                    return number2 - number1;
                }
            }
        });
    }

    public static void rePosite(GameFrame f, ArrayList<Poker> list, int sequenece){
        Point p = new Point();
        //0-左电脑 1-玩家 2-右电脑
        //使用list.size()来判断牌所在的位置，使得在出完牌后也能调整牌的位置。
        if (sequenece == 0) {
            p.x = 80;
            p.y = 225 - (list.size() + 1) * 15 / 2;
        }
        if (sequenece == 1) {
            p.x = 490 - (list.size() + 1) * 21 / 2;
            p.y = 570;
        }
        if (sequenece == 2) {
            p.x = 849;
            p.y = 225 - (list.size() + 1) * 15 / 2;
        }
        int len = list.size();
        for (int i = 0; i < len; i++) {
            Poker poker = list.get(i);
            Move.move(poker, poker.getLocation(), p);
            f.container.setComponentZOrder(poker, 0);
            if (sequenece == 1)
                p.x += 21;
            else
                p.y += 15;
        }
    }

    public static void move(Poker poker, Point from, Point to) {
        if (to.x != from.x) {
            double k = (1.0) * (to.y - from.y) / (to.x - from.x);
            double b = to.y - to.x * k;
            int flag = 0;
            if (from.x < to.x)
                flag = 20;
            else {
                flag = -20;
            }
            for (int i = from.x; Math.abs(i - to.x) > 20; i += flag) {
                double y = k * i + b;

                poker.setLocation(i, (int) y);
                try {
                    Thread.sleep(5);
                } catch (InterruptedException e) {
                    e.printStackTrace();
                }
            }
        }
        poker.setLocation(to);
    }
}

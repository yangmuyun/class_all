package Game_System.Frame;

import java.awt.*;
import Game_System.Frame.Poker.Poker;
public class Move {
    //模拟发牌使时的移动动画
    public static void move(Poker poker, Point from, Point to) {
        if (to.x != from.x) {
            //斜率
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
                //重新设置牌的位置
                poker.setLocation(i, (int) y);
                //使线程停止休眠5毫秒，使得循环模拟出一帧一帧的动画
                try {
                    Thread.sleep(3);
                } catch (InterruptedException e) {
                    e.printStackTrace();
                }
            }
        }
        poker.setLocation(to);
    }
}

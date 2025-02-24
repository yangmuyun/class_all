package Game_System.Frame;

import Game_System.Frame.Poker.Poker;

import javax.swing.*;
import java.awt.*;

public class Player extends Thread{
    //游戏主界面
    GameFrame gameJFrame;

    //倒计时是否能走
    boolean isTime = true;

    //倒计时
    int seconds;

    public Player(GameFrame m, int seconds){
        this.gameJFrame = m;
        this.seconds = seconds;
    }

    @Override
    public void run() {
        //倒计时 并且 玩家没有点击抢地主按钮
        while(seconds > -1 && isTime){
            gameJFrame.time[1].setText("倒计时:" + seconds);
            seconds--;
            mySleep(1);
        }
        if(seconds == -1){
            gameJFrame.time[1].setText("不 抢");
        }
        //将抢地主按钮隐藏
        gameJFrame.beland.setVisible(false);
        gameJFrame.belord.setVisible(false);


        //如果用户点击了抢地主
        if (gameJFrame.time[1].getText().equals("抢地主")){
            gameJFrame.playerCard.get(1).addAll(gameJFrame.lordCard);
            turnlordlist(true);
            mySleep(2);
            Order.order(gameJFrame.playerCard.get(1));
            Order.rePosite(gameJFrame,gameJFrame.playerCard.get(1),1);
            setlord(1);
        }else{
            //判断电脑玩家牌的分数谁高 谁高谁抢地主
            if(PokerOperation.getScore(gameJFrame.playerCard.get(0))< PokerOperation.getScore(gameJFrame.playerCard.get(2))){
                mySleep(1);
                gameJFrame.time[2].setText("抢地主");
                gameJFrame.time[2].setVisible(true);
                gameJFrame.playerCard.get(2).addAll(gameJFrame.lordCard);
                turnlordlist(true);
                mySleep(2);
                Order.order(gameJFrame.playerCard.get(2));
                Order.rePosite(gameJFrame,gameJFrame.playerCard.get(2),2);
                turnlordlist(false);
                setlord(2);
            }else{
                mySleep(1);
                gameJFrame.time[0].setText("抢地主");
                gameJFrame.time[0].setVisible(true);
                gameJFrame.playerCard.get(0).addAll(gameJFrame.lordCard);
                turnlordlist(true);
                mySleep(2);
                Order.order(gameJFrame.playerCard.get(0));
                Order.rePosite(gameJFrame,gameJFrame.playerCard.get(0),0);
                turnlordlist(false);
                setlord(0);
            }
        }
        //抢完地主后将玩家的手牌全部设置为可点击
        for (Poker pokers : gameJFrame.playerCard.get(1)) {
            pokers.setCanClick(true);
        }
        //初始化time标签
        initTime();
        //turn变量表示到谁的出牌回合
        int turn = gameJFrame.lordflag;
        //出现自动出牌按钮
        gameJFrame.auto.setVisible(true);
        while(true){
            //玩家回合
            if(turn == 1){
                //如果两个电脑都不出牌 玩家的不要按钮就不能进行交互
                if(gameJFrame.time[0].getText().equals("不要")&&gameJFrame.time[2].getText().equals("不要")){
                    gameJFrame.noCard.setEnabled(false);
                }else{
                    gameJFrame.noCard.setEnabled(true);
                }
                visPush(true);
                pushWait(30,1);
                visPush(false);
                turn = 2;
                if(win()){
                    break;
                }
            }
            //电脑回合
            if(turn == 0){
                computer0();
                turn = 1;
                if(win()){
                    break;
                }
            }
            if(turn == 2){
                computer2();
                turn = 0;
                if(win()){
                    break;
                }
            }
        }
        gameJFrame.again.setVisible(true);
        gameJFrame.quit.setVisible(true);
    }

    //将sleep放进自己方法，以秒作为单位
    public void mySleep(int seconds){
        try {
            sleep(seconds*1000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
    }
    //展示地主的三张牌
    public void turnlordlist(boolean turn){
        for (int i = 0;i<3;i++){
            if(turn){
                gameJFrame.lordCard.get(i).turnFront();
            }else{
                gameJFrame.lordCard.get(i).turnRear();
            }
            gameJFrame.lordCard.get(i).setCanClick(true);
        }
    }
    //设置地主
    public void setlord(int i){
        Point dip = new Point();
        //左地主
        if(i == 0){
            dip.x = 80;
            dip.y = 30;
            gameJFrame.lordflag = 0;
        }
        //玩家地主
        if(i == 1){
            dip.x = 200;
            dip.y = 600;
            gameJFrame.lordflag = 1;
        }
        //右地主
        if(i == 2){
            dip.x = 880;
            dip.y = 30;
            gameJFrame.lordflag = 2;
        }
        gameJFrame.dizhu.setLocation(dip);
        gameJFrame.dizhu.setVisible(true);
    }
    //设置出牌和不要牌的状态
    public void visPush(boolean is){
        gameJFrame.pushCard.setVisible(is);
        gameJFrame.noCard.setVisible(is);
    }
    //初始化倒计时label 为游戏开始做准备
    public void initTime(){
        for (int i = 0;i < 3;i++){
            gameJFrame.time[i].setText("不要");
            gameJFrame.time[i].setVisible(false);
        }
    }
    //出牌等待
    public void pushWait(int time,int player){
        if(gameJFrame.currentCard.get(player).size()>0){
            PokerOperation.hideCards(gameJFrame.currentCard.get(player));
        }
        if (player == 1) {
            int i = time;
            //当还没有出完牌
            while (gameJFrame.nextPlayer == false && i >= 0) {

                gameJFrame.time[player].setText("倒计时:" + i);
                gameJFrame.time[player].setVisible(true);
                mySleep(1);
                i--;
                //开启了自动出牌
                if(gameJFrame.autoFlag == true){
                    visPush(false);
                    gameJFrame.time[player].setVisible(false);
                    PokerOperation.computerShowCard(1,gameJFrame);
                    if(gameJFrame.time[player].getText().equals("不要")){
                        gameJFrame.time[player].setVisible(true);
                    }
                    mySleep(1);
                    return;
                }
            }
            if (i == -1 && player == 1 && gameJFrame.time[0].getText().equals("不要") && gameJFrame.time[2].getText().equals("不要")) {
                //自动出牌
                PokerOperation.computerShowCard(1,gameJFrame);
            }else if(i == -1){
                gameJFrame.time[1].setText("不要");
                gameJFrame.time[1].setVisible(true);
                mySleep(1);
            }
            gameJFrame.nextPlayer = false;
        } else {
            for (int i = time; i >= 0; i--) {
                mySleep(1);
                gameJFrame.time[player].setText("倒计时:" + i);
                gameJFrame.time[player].setVisible(true);
            }
        }
        gameJFrame.time[player].setVisible(false);
    }
    //判断玩家是否胜利
    public boolean win() {
        for (int i = 0; i < 3; i++) {
            if (gameJFrame.playerCard.get(i).size() == 0) {
                String s;
                if (i == gameJFrame.lordflag) {
                    s = "恭喜地主，胜利了!";
                } else {
                    s = "恭喜农民，胜利了!";
                }
                for (int j = 0; j < gameJFrame.playerCard.get((i + 1) % 3).size(); j++)
                    gameJFrame.playerCard.get((i + 1) % 3).get(j).turnFront();
                for (int j = 0; j < gameJFrame.playerCard.get((i + 2) % 3).size(); j++)
                    gameJFrame.playerCard.get((i + 2) % 3).get(j).turnFront();
                JOptionPane.showMessageDialog(gameJFrame, s);
                return true;
            }
        }
        return false;
    }
    public void computer0(){
        pushWait(1,0);
        PokerOperation.computerShowCard(0,gameJFrame);
    }
    public void computer2(){
        pushWait(1,2);
        PokerOperation.computerShowCard(2,gameJFrame);
    }
}

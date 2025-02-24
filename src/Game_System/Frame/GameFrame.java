package Game_System.Frame;

import Game_System.Frame.Poker.Poker;

import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.util.ArrayList;
import java.util.Collections;

public class GameFrame extends JFrame implements ActionListener {
    //获得组件容器
    public Container container = null;

    //抢地主和不枪地主的按钮
    JButton beland = new JButton("不 抢");
    JButton belord = new JButton("抢地主");

    //用于记录抢不抢地主
    int lordflag;

    //地主图标
    JLabel dizhu;

    //出牌和不要的按钮
    JButton pushCard = new JButton("出 牌");
    JButton noCard = new JButton("不 要");

    //装所有的扑克牌
    ArrayList<Poker> pokerList = new ArrayList();

    //地主的三张牌
    ArrayList<Poker> lordCard = new ArrayList();

    //当前玩家的手牌 0下标为左边电脑 1下标为玩家 2下标为右边电脑
    ArrayList<ArrayList<Poker>> playerCard = new ArrayList();

    //玩家当前要出的牌
    ArrayList<ArrayList<Poker>> currentCard = new ArrayList();

    //三个玩家前方的文本提示
    //0-左边的电脑玩家 1-中间的自己 2-右边的电脑玩家
    JTextField time[] = new JTextField[3];

    //用户操作的线程
    Player play;

    //记录当前玩家是否出完牌
    boolean nextPlayer = false;

    //自动出牌模式
    JButton auto;
    boolean autoFlag = false;

    //再来一把和退出游戏
    JButton again;
    JButton quit;

    public GameFrame(){
        //加载游戏界面
        initJframe();
        //加载组件
        initContent();
        //使界面出现
        this.setVisible(true);
        //发牌
        initCard();
        //开始游戏
        startGame();
    }

    public void initJframe(){
        //设置标题
        setTitle("斗地主小游戏");
        //设置大小
        setSize(1000,750);
        //关闭方式
        setDefaultCloseOperation(3);
        //组件布局方式
        setLayout(null);
        //设置窗口无法进行调节
        setResizable(false);
        //界面居中
        setLocationRelativeTo(null);
        //获得组件容器
        container = this.getContentPane();
    }

    public void initContent(){
        //抢地主按钮
        belord.setBounds(350,510,100,30);
        belord.addActionListener(this);
        container.add(belord);
        belord.setVisible(false);

        //不抢地主按钮
        beland.setBounds(550,510,100,30);
        beland.addActionListener(this);
        container.add(beland);
        beland.setVisible(false);

        //出牌按钮
        pushCard.setBounds(350,510,100,30);
        pushCard.addActionListener(this);
        container.add(pushCard);
        pushCard.setVisible(false);

        //不要按钮
        noCard.setBounds(550,510,100,30);
        noCard.addActionListener(this);
        container.add(noCard);
        noCard.setVisible(false);
        //倒计时
        for (int i = 0; i < 3; i++) {
            time[i] = new JTextField("倒计时:");
            time[i].setEditable(false);
            time[i].setVisible(false);
            container.add(time[i]);
        }
        time[0].setBounds(180, 260, 80, 25);
        time[1].setBounds(460, 480, 80, 25);
        time[2].setBounds(760, 260, 80, 25);
        //成为地主的图标
        dizhu = new JLabel(new ImageIcon("image/dizhu.png"));
        dizhu.setSize(40,40);
        dizhu.setVisible(false);
        container.add(dizhu);
        //自动出牌按钮
        auto = new JButton("开启自动出牌");
        auto.setBounds(850,680,120,30);
        auto.setVisible(false);
        auto.addActionListener(this);
        container.add(auto);
        //再来一把
        again = new JButton("继续游戏");
        again.setBounds(360,330,120,35);
        again.setVisible(false);
        again.addActionListener(this);
        container.add(again);
        //退出
        quit = new JButton("退出游戏");
        quit.setBounds(520,330,120,35);
        quit.setVisible(false);
        quit.addActionListener(this);
        container.add(quit);
    }

    public void initCard(){
        //将所有牌存入pokerList中 并显示在屏幕中
        for(int i=1;i<=5;i++){
            for(int j=1;j<13;j++){
                if(i==5 && j > 2){
                    break;
                }
                Poker poker = new Poker(this,i + "-" + j,false);
                poker.setLocation(470,250);
                pokerList.add(poker);
                container.add(poker);
            }
        }
        //System.out.println(pokerList.toString());
        //洗牌
        Collections.shuffle(pokerList);
        //System.out.println(pokerList.toString());

        //创建三个集合用来装三个玩家的牌，并把三个小集合放到大集合中方便管理
        ArrayList<Poker> player0 = new ArrayList<>();
        ArrayList<Poker> player1 = new ArrayList<>();
        ArrayList<Poker> player2 = new ArrayList<>();
        //发牌
        for(int i=0;i<pokerList.size();i++){
            Poker poker = pokerList.get(i);

            //地主牌
            if(i<=2){
                Move.move(poker, poker.getLocation(), new Point(390 + (80 * i), 10));
                //把底牌添加到集合中
                lordCard.add(poker);
                continue;
            }
            //给三个玩家发牌
            if (i % 3 == 0) {
                //给左边的电脑发牌
                Move.move(poker, poker.getLocation(), new Point(80, 80 + i * 5));
                player0.add(poker);
            } else if (i % 3 == 1) {
                //把自己的牌展示正面
                poker.turnFront();
                //给中间的自己发牌
                Move.move(poker, poker.getLocation(), new Point(265 + i * 7, 570));
                player1.add(poker);
            } else if (i % 3 == 2) {
                //给右边的电脑发牌
                Move.move(poker, poker.getLocation(), new Point(849, 80 + i * 5));
                player2.add(poker);
            }
            //设置poker的z轴顺序值，使后加入的poker叠放在先加入的上面
            container.setComponentZOrder(poker,0);
        }
        playerCard.add(player0);
        playerCard.add(player1);
        playerCard.add(player2);
        //排序
        for(int i=0;i<3;i++){
            Order.order(playerCard.get(i));
            Order.rePosite(this,playerCard.get(i),i);
        }

    }

    public void startGame(){
        //创建三个集合用来装三个玩家准备要出的牌
        for (int i = 0; i < 3; i++) {
            ArrayList<Poker> list = new ArrayList<>();
            //添加到大集合中方便管理
            currentCard.add(list);
        }
        //显示出抢地主的按钮
        belord.setVisible(true);
        beland.setVisible(true);

        //展示自己前面的倒计时文本
        time[1].setVisible(true);

        //开启操作线程
        play = new Player(this,30);

        play.start();

    }

    @Override
    public void actionPerformed(ActionEvent e) {
        //判断抢地主还是不抢
        if(e.getSource() == belord){
            time[1].setText("抢地主");
            play.isTime = false;
        }else if(e.getSource()== beland){
            time[1].setText("不 抢");
            play.isTime = false;
        }else if(e.getSource()==pushCard){

            //创建一个临时的集合，用来存放当前要出的牌
            ArrayList<Poker> c = new ArrayList<>();
            //获取中自己手上所有的牌
            ArrayList<Poker> player2 = playerCard.get(1);
            //遍历手上的牌，把要出的牌都放到临时集合中
            for (int i = 0; i < player2.size(); i++) {
                Poker poker = player2.get(i);
                if (poker.isClicked()) {
                    c.add(poker);
                }
            }

            //测试代码
            //System.out.println(PokerOperation.judgeType(c));

            //用于判断是否可以出牌
            int canPush = 0;

            //如果两个电脑都不要的话
            if(time[0].getText().equals("不要") && time[2].getText().equals("不要")){
                //如果出的牌是合法的话
                if(PokerOperation.judgeType(c)!=PokerType.p0){
                    canPush = 1;
                }
            }else{
                canPush = PokerOperation.checkCanPush(c,currentCard,this);
            }
            //测试代码
            //System.out.println(canPush);

            if(canPush == 1){
                currentCard.set(1,c);
                player2.removeAll(c);

                //计算坐标并移动牌
                //移动的目的是要出的牌移动到上方
                Point point = new Point();
                point.x = 480 - (c.size() + 1) * 15 / 2;
                point.y = 400;
                for (int i = 0, len = c.size(); i < len; i++) {
                    Poker poker = c.get(i);
                    Order.move(poker, poker.getLocation(), point);
                    point.x += 15;
                }
                //重新摆放剩余的牌
                Order.rePosite(this, player2, 1);
                //赢藏文本提示
                time[1].setVisible(false);
                //下一个玩家可玩
                this.nextPlayer = true;
            }
        }else if(e.getSource()==noCard){
            //点击不要
            this.nextPlayer = true;
            currentCard.get(1).clear();
            time[1].setText("不要");
        }else if(e.getSource() == auto){
            if(autoFlag == false){
                //System.out.println("open");
                autoFlag = true;
                auto.setText("关闭自动出牌");
            }else{
                //System.out.println("close");
                autoFlag = false;
                auto.setText("开启自动出牌");
            }
        }else if(e.getSource() == again){
            dispose();
            SwingUtilities.invokeLater(() -> {
                new Thread(() -> {
                    new GameFrame().setVisible(true);
                }).start();
            });
        }else if(e.getSource() == quit){
            System.exit(0);
        }
    }
}

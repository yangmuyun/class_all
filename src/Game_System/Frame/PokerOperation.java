package Game_System.Frame;

import Game_System.Frame.Poker.Poker;

import java.awt.*;
import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

public class PokerOperation {
    //计算电脑手牌的得分
    public static int getScore(ArrayList<Poker> list){
        int score = 0;
        int last = -1;
        int temp = -1;
        int count = 0;
        int joker = 0;
        //大王小王-5分 王炸-10分 A-2分 2-3分 三对-2分 炸弹-5分
        for (int i = 0;i< list.size();i++){
            Poker poker = list.get(i);
            if(poker.getName().substring(0,1).equals("5")){
                score += 5;
                joker++;
                if(joker==2){
                    score += 10;
                }
            }else{
                temp = Integer.parseInt(poker.getName().substring(2));
                if(temp == 1){
                    score += 2;
                }else if(temp == 2){
                    score += 3;
                }
                if((temp != last || i== list.size())&& last!=-1){
                    if(count == 3){
                        score += 2;
                    }else if(count == 4){
                        score += 5;
                    }
                    last = temp;
                    count = 1;
                }else{
                    last = temp;
                    count++;
                }
            }
        }
        return score;
    }
    //list为玩家要出的牌
    public static PokerType judgeType(ArrayList<Poker> list){
        int len = list.size();
        //单双三炸弹
        if(len <= 4 && len>0){
            if(Poker.getValue(list.get(0)) == Poker.getValue(list.get(len-1))){
                switch(len){
                    case 1:
                        return PokerType.p1;
                    case 2:
                        return PokerType.p2;
                    case 3:
                        return PokerType.p3;
                    case 4:
                        return PokerType.p4;
                }
            }
            //王炸
            if(len == 2 && Poker.getColor(list.get(1))==5){
                return PokerType.p4;
        }
            //三带一
            if(len==4 && ((Poker.getValue(list.get(0))==Poker.getValue(list.get(len-2)))
                    ||(Poker.getValue(list.get(1))==Poker.getValue(list.get(len-1))))){
                return PokerType.p31;
            }else{
                return PokerType.p0;
            }
        }
        if(len >= 5){
            LinkedHashMap<Integer,Integer> hs = new LinkedHashMap<>();
            for (Poker poker : list) {
                if(hs.containsKey(Poker.getValue(poker))){
                    int value = hs.get(Poker.getValue(poker))+1;
                    hs.put(Poker.getValue(poker),value);
                }else{
                    hs.put(Poker.getValue(poker),1);
                }
            }
            //三带二
            if(len == 5){
                if(hs.size()==2){
                    for (Integer value : hs.values()) {
                        if(value==2 || value==3){
                            return PokerType.p32;
                        }else{
                            break;
                        }
                    }
                }
            }
            //四带二
            if(len == 6){
                for (Integer value : hs.values()) {
                    if(value == 4){
                        return PokerType.p411;
                    }
                }
            }
            //四带两对
            if(len == 8){
                if(hs.size()==3){
                    boolean is4 = false;
                    boolean is2 = false;
                    for (Integer value : hs.values()) {
                        if(value == 4){
                           is4 = true;
                        }else if(value == 2){
                            is2 =true;
                        }
                    }
                    if(is4 && is2){
                        return PokerType.p422;
                    }
                }
            }
            //顺子
            if(len >= 5){
                if (hs.size() >= 5) {
                    int last_key = -1;
                    boolean isKey = true;
                    boolean isValue = true;
                    for (Map.Entry<Integer, Integer> entry : hs.entrySet()) {
                        if(last_key != -1){
                            if(entry.getKey() != last_key-1){
                                isKey = false;
                                break;
                            }
                        }
                        if(entry.getValue()!=1){
                            isValue = false;
                            break;
                        }
                        last_key = entry.getKey();
                    }
                    if(isKey && isValue){
                        return PokerType.p123;
                    }
                }
            }
            //连对
            if(len >= 6){
                if(hs.size()>=3){
                    int last_key = -1;
                    boolean isKey = true;
                    boolean isValue = true;
                    for (Map.Entry<Integer, Integer> entry : hs.entrySet()) {
                        if(last_key != -1){
                            if(entry.getKey() != last_key-1){
                                isKey = false;
                                break;
                            }
                        }
                        if(entry.getValue()!=2){
                            isValue = false;
                            break;
                        }
                        last_key = entry.getKey();
                    }
                    if(isKey && isValue){
                        return PokerType.p112233;
                    }
                }
            }
            //飞机
            if(len>=6){
                if(hs.size()>=2){
                    int last_key = -1;
                    boolean isKey = true;
                    boolean isValue = true;
                    for (Map.Entry<Integer, Integer> entry : hs.entrySet()) {
                        if(last_key != -1){
                            if(entry.getKey() != last_key-1){
                                isKey = false;
                                break;
                            }
                        }
                        if(entry.getValue()!=3){
                            isValue = false;
                            break;
                        }
                        last_key = entry.getKey();
                    }
                    if(isKey && isValue){
                        return PokerType.p111222;
                    }
                }
            }
            //飞机带单
            if(len>=8){
                if(hs.size()>=3){
                    int last_key = -1;
                    boolean isKey = true;
                    int numKey = 0;
                    for (Map.Entry<Integer, Integer> entry : hs.entrySet()) {
                        if(entry.getValue()==3){
                            if(last_key != -1){
                                if(entry.getKey() != last_key-1){
                                    isKey = false;
                                    break;
                                }
                            }
                            last_key = entry.getKey();
                            numKey++;
                        }

                    }
                    if(isKey){
                        if(len-3*numKey==numKey){
                            return PokerType.p11122234;
                        }
                    }
                }
            }
            //飞机带双
            if(len>=10){
                if(hs.size()>=3){
                    int last_key = -1;
                    boolean isKey = true;
                    boolean isValue = true;
                    int numKey = 0;
                    for (Map.Entry<Integer, Integer> entry : hs.entrySet()) {
                        if(entry.getValue()==3){
                            if(last_key != -1){
                                if(entry.getKey() != last_key-1){
                                    isKey = false;
                                    break;
                                }
                            }
                            last_key = entry.getKey();
                            numKey++;
                        }else if(entry.getValue()!=2){
                            isValue = false;
                        }
                    }
                    if(isKey && isValue){
                        if(len-3*numKey==2*numKey){
                            return PokerType.p1112223344;
                        }
                    }
                }
            }
        }
        return PokerType.p0;
    }

    //用于判断玩家是否能出牌
    public static int checkCanPush(ArrayList<Poker> temp,ArrayList<ArrayList<Poker>> current,GameFrame m){
        ArrayList<Poker> last;
        //如果左电脑不要 则右边最后出牌

        if(m.time[0].getText().equals("不要")){
            last = current.get(2);
        }else{
            last = current.get(0);
        }
        PokerType ptemp = judgeType(temp);
        PokerType plast = judgeType(last);

        //temp不是炸弹 并且牌数不一样
        if(ptemp != PokerType.p4 && temp.size() != last.size()){
            return 0;
        }
        //temp不是炸弹 并且和last牌型不一样
        if(ptemp != PokerType.p4 && ptemp != plast){
            return 0;
        }
        //temp是炸弹 last不是炸弹
        if(ptemp == PokerType.p4){
            //王炸
            if(temp.size() == 2){
                return 1;
            }else if(plast != PokerType.p4){
                return 1;
            }
        }
        //单双三炸弹
        if(ptemp == PokerType.p1 || ptemp == PokerType.p2 || ptemp == PokerType.p3 || ptemp == PokerType.p4){
            if(Poker.getValue(temp.get(0)) > Poker.getValue(last.get(0))){
                return 1;
            }else{
                return 0;
            }
        }
        //剩余的牌型
        if(ptemp == PokerType.p31 || ptemp == PokerType.p32 || ptemp == PokerType.p411 || ptemp == PokerType.p422
                || ptemp == PokerType.p11122234 || ptemp == PokerType.p1112223344){
            ArrayList<Poker> l1 = getOrder1(temp);
            ArrayList<Poker> l2 = getOrder1(last);
            if(Poker.getValue(l1.get(0))>Poker.getValue(l2.get(0))){
                return 1;
            }else{
                return 0;
            }
        }
        return 1;
    }

    //获得排序后的List，用于判断牌型大小的牌在 0 Index，返回根据斗地主游戏排序后的list
    public static ArrayList getOrder1(ArrayList<Poker> list){
        ArrayList<Poker> ordered = new ArrayList<Poker>();
        int maxNum = -1,minNum = 5;
        int[] cnt = new int[20];
        //初始化数组
        for (int i = 0; i < 20; i++) {
            cnt[i] = 0;
        }
        //计算牌的个数
        for (Poker poker : list) {
            int count = ++cnt[Poker.getValue(poker)];
            if(count > maxNum){
                maxNum = count;
            }
            if(count <minNum){
                minNum = count;
            }
        }
        while(maxNum >= minNum){
            for (int i = 19; i >=0 ; i--) {
                if(cnt[i] == maxNum){
                    Poker tempPoker = null;
                    for (Poker poker : list) {
                        if(Poker.getValue(poker) == i){
                            tempPoker = poker;
                            break;
                        }
                    }
                    for (int j = 0; j < maxNum; j++) {
                            ordered.add(tempPoker);
                    }
                }
            }
            maxNum--;
        }
        return ordered;
    }

    //将上一轮出的牌隐藏
    public static void hideCards(ArrayList<Poker> list) {
        for (int i = 0, len = list.size(); i < len; i++) {
            list.get(i).setVisible(false);
        }
    }

    //电脑出牌
    public static void computerShowCard(int role,GameFrame gameJFrame){
        int orders[] = new int[] { 4, 3, 2, 1, 5 };
        Model model = getModel(gameJFrame.playerCard.get(role), orders);
        //调试代码
        //System.out.println(model.toString());
        ArrayList<String> list = new ArrayList<>();
        //当上两家都选择不要时，根据上面建立的model来出牌
        //在model中的排序为从大到小排序，当两家不要时，电脑从小的牌开始出
        if (gameJFrame.time[(role + 1) % 3].getText().equals("不要") && gameJFrame.time[(role + 2) % 3].getText().equals("不要")) {
            if (model.a123.size() > 0) {
                //顺子
                list.add(model.a123.get(model.a123.size() - 1));
            } else if (model.a3.size() > 0) {
                if (model.a1.size() > 0) {
                    //三带一
                    list.add(model.a1.get(model.a1.size() - 1));
                } else if (model.a2.size() > 0) {
                    //三带二
                    list.add(model.a2.get(model.a2.size() - 1));
                }
                list.add(model.a3.get(model.a3.size() - 1));
            } else if (model.a112233.size() > 0) {
                //连对
                list.add(model.a112233.get(model.a112233.size() - 1));
            } else if (model.a111222.size() > 0) {
                //飞机
                String name[] = model.a111222.get(0).split(",");
                if (name.length / 3 <= model.a1.size()) {
                    //带单
                    list.add(model.a111222.get(model.a111222.size() - 1));
                    for (int i = 0; i < name.length / 3; i++)
                        list.add(model.a1.get(i));
                } else if (name.length / 3 <= model.a2.size()) {
                    //带双
                    list.add(model.a111222.get(model.a111222.size() - 1));
                    for (int i = 0; i < name.length / 3; i++)
                        list.add(model.a2.get(i));
                }

            } else if (model.a2.size() > (model.a111222.size() * 2 + model.a3.size())) {
                //对子
                list.add(model.a2.get(model.a2.size() - 1));
            } else if (model.a1.size() > (model.a111222.size() * 2 + model.a3.size())) {
                //单
                list.add(model.a1.get(model.a1.size() - 1));
            } else if (model.a4.size() > 0) {
                //最后出四张
                int sizea1 = model.a1.size();
                int sizea2 = model.a2.size();
                if (sizea1 >= 2) {
                    //四带单
                    list.add(model.a1.get(sizea1 - 1));
                    list.add(model.a1.get(sizea1 - 2));
                    list.add(model.a4.get(0));

                } else if (sizea2 >= 2) {
                    //四带对
                    list.add(model.a2.get(sizea1 - 1));
                    list.add(model.a2.get(sizea1 - 2));
                    list.add(model.a4.get(0));

                } else {
                    //炸弹
                    list.add(model.a4.get(0));

                }

            }
        }else{
            //有出牌的话
            //自己不是地主的话 并且自己的牌多于两张的话
            if (role != gameJFrame.lordflag && gameJFrame.playerCard.get(role).size() > 2) {
                //pu用来记录能不能出牌 0为能出 1不能出
                int pu = 0;
                //如果地主不要的话 继续让队友出牌
                if (gameJFrame.time[gameJFrame.lordflag].getText().equals("不要")) {
                    pu = 1;
                }
                //如果下家是地主
                if ((role + 1) % 3 == gameJFrame.lordflag) {
                    //如果上家出的不是单牌和双 并且地主的手牌小于3的话 就让队友继续出
                    if ((judgeType(gameJFrame.currentCard.get((role + 2) % 3)) != PokerType.p1
                            || judgeType(gameJFrame.currentCard.get((role + 2) % 3)) != PokerType.p2)
                            && gameJFrame.playerCard.get(gameJFrame.lordflag).size() < 3)
                        pu = 1;
                    //如果上家出牌了 并且上家的牌大于等于A时 就选择不出
                    if (gameJFrame.currentCard.get((role + 2) % 3).size() > 0
                            && Poker.getValue(gameJFrame.currentCard.get((role + 2) % 3).get(0)) > 13)
                        pu = 1;
                }
                //不出
                if (pu == 1) {
                    gameJFrame.time[role].setVisible(true);
                    gameJFrame.time[role].setText("不要");
                    return;
                }
            }

            //can是1的话代表开启紧急模式 使用出大的牌
            //在对手牌少于5张的情况下开启紧急模式
            int can = 0;
            if (role == gameJFrame.lordflag) {
                //如果自己是地主，观察农民的牌
                if (gameJFrame.playerCard.get((role + 1) % 3).size() <= 5 || gameJFrame.playerCard.get((role + 2) % 3).size() <= 5)
                    can = 1;
            } else {
                //如果自己是农民，观察地主的牌
                if (gameJFrame.playerCard.get(gameJFrame.lordflag).size() <= 5)
                    can = 1;
            }
            //玩家上面出的牌
            ArrayList<Poker> last;
            if (gameJFrame.time[(role + 2) % 3].getText().equals("不要"))
                last = gameJFrame.currentCard.get((role + 1) % 3);
            else
                last = gameJFrame.currentCard.get((role + 2) % 3);

            //获得出的牌型
            PokerType cType = judgeType(last);

            if (cType == PokerType.p1) {
                //单张
                if (can == 1)
                    //触发紧急模式时
                    //重新计算model将出单张的牌的优先级设置为最高
                    model = getModel(gameJFrame.playerCard.get(role), new int[] { 1, 4, 3, 2, 5 });
                Computer_1(model.a1, last, list, role);
            } else if (cType == PokerType.p2) {
                if (can == 1)
                    //触发紧急模式时
                    //重新计算model将出对子的牌的优先级设置为最高
                    model = getModel(gameJFrame.playerCard.get(role), new int[] { 2, 4, 3, 5, 1 });
                Computer_1(model.a2, last, list, role);
            } else if (cType == PokerType.p3) {
                //三张
                Computer_1(model.a3, last, list, role);
            } else if (cType == PokerType.p4) {
                //炸弹
                Computer_1(model.a4, last, list, role);
            } else if (cType == PokerType.p31) {
                //三带一
                if (can == 1)
                    model = getModel(gameJFrame.playerCard.get(role), new int[] { 3, 1, 4, 2, 5 });
                Computer_2(model.a3, model.a1, last, list, role);
            } else if (cType == PokerType.p32) {
                //三带二
                if (can == 1)
                    model = getModel(gameJFrame.playerCard.get(role), new int[] { 3, 2, 4, 5, 1 });
                Computer_2(model.a3, model.a2, last, list, role);
            } else if (cType == PokerType.p411) {
                //四带单
                Computer_4(model.a4, model.a1, last, list, role);
            }

            else if (cType == PokerType.p422) {
                //四带对
                Computer_4(model.a4, model.a2, last, list, role);
            }

            else if (cType == PokerType.p123) {
                //顺子
                if (can == 1)
                    model = getModel(gameJFrame.playerCard.get(role), new int[] { 5, 3, 2, 4, 1 });
                Computer_3(model.a123, last, list, role);
            }

            else if (cType == PokerType.p112233) {
                //连对
                if (can == 1)
                    model = getModel(gameJFrame.playerCard.get(role), new int[] { 2, 4, 3, 5, 1 });
                Computer_3(model.a112233, last, list, role);
            }
            else if(cType == PokerType.p111222){
                //纯飞机
                Computer_3(model.a111222, last, list, role);
            }
            else if (cType == PokerType.p11122234) {
                //飞机
                Computer_5(model.a111222, model.a1, last, list, role);
            }

            else if (cType == PokerType.p1112223344) {
                //飞机
                Computer_5(model.a111222, model.a2, last, list, role);
            }
            //如果没有能出的拍的话 并且进入紧急模式了
            if (list.size() == 0 && can == 1) {
                //有炸弹出炸弹
                int len4 = model.a4.size();
                if (len4 > 0)
                    list.add(model.a4.get(len4 - 1));
            }

        }
        gameJFrame.currentCard.get(role).clear();
        if (list.size() > 0) {
            Point point = new Point();
            if (role == 0){
                point.x = 240;
                point.y = 250 - (list.size() + 1) * 15 / 2;
            }

            if (role == 2){
                point.x = 700;
                point.y = 250 - (list.size() + 1) * 15 / 2;
            }

            if (role == 1) {
                point.x = 460 - (gameJFrame.currentCard.get(1).size() + 1) * 15 / 2;
                point.y = 400;
            }

            ArrayList<Poker> temp = new ArrayList<>();
            for (int i = 0, len = list.size(); i < len; i++) {
                List<Poker> pokers = getCardByName(gameJFrame.playerCard.get(role), list.get(i));
                for (Poker poker : pokers) {
                    temp.add(poker);
                }
            }
            //给牌排序
            temp = getOrder2(temp);
            for (Poker poker : temp) {
                Move.move(poker, poker.getLocation(), point);
                if(role != 1){
                    point.y += 15;
                }else{
                    point.x += 15;
                }
                gameJFrame.container.setComponentZOrder(poker, 0);
                gameJFrame.currentCard.get(role).add(poker);
                gameJFrame.playerCard.get(role).remove(poker);
            }
            Order.rePosite(gameJFrame, gameJFrame.playerCard.get(role), role);
        } else {
            gameJFrame.time[role].setVisible(true);
            gameJFrame.time[role].setText("不要");
        }
        for (Poker poker : gameJFrame.currentCard.get(role))
            poker.turnFront();
    }

    //根据手牌获得可以出的牌型 order代表了牌型的优先级 order中在前面的牌型优先级高
    public static Model getModel(ArrayList<Poker> list, int[] orders) {
        ArrayList list2 = new ArrayList<>(list);
        Model model = new Model();
        for (int i = 0; i < orders.length; i++)
            switch (orders[i]) {
                case 1:
                    getSingle(list2, model);
                    break;
                case 2:
                    getTwo(list2, model);
                    getTwoTwo(list2, model);
                    break;
                case 3:
                    getThree(list2, model);
                    getPlane(list2, model);
                    break;
                case 4:
                    getBoomb(list2, model);
                    break;
                case 5:
                    get123(list2, model);
                    break;
            }
        return model;
    }

    //获得可以出的单牌
    public static void getSingle(ArrayList<Poker> list, Model model) {
        //del为代表已经使用过的牌，在添加到出牌的牌型后要删除掉
        ArrayList<Poker> del = new ArrayList<>();
        for (int i = 0, len = list.size(); i < len; i++) {
            model.a1.add(list.get(i).getName());
            del.add(list.get(i));
        }
        list.removeAll(del);
    }
    //获得可以出的对子
    public static void getTwo(ArrayList<Poker> list, Model model) {
        ArrayList<Poker> del = new ArrayList<>();
        for (int i = 0, len = list.size(); i < len; i++) {
            if (i + 1 < len && Poker.getValue(list.get(i)) == Poker.getValue(list.get(i + 1))) {
                String s = list.get(i).getName() + ",";
                s += list.get(i + 1).getName();
                model.a2.add(s);
                for (int j = i; j <= i + 1; j++)
                    del.add(list.get(j));
                i = i + 1;
            }
        }
        list.removeAll(del);
    }
    //获得可以出的连对
    public static void getTwoTwo(ArrayList<Poker> list, Model model) {
        ArrayList<String> del = new ArrayList<>();
        //已经有的对子
        ArrayList<String> l = model.a2;
        //对子数量小于三
        if (l.size() < 3)
            return;
        Integer dou[] = new Integer[l.size()];
        //将有对子的数字存入s数组中
        for (int i = 0, len = l.size(); i < len; i++) {
            String[] name = l.get(i).split(",");
            dou[i] = Integer.parseInt(name[0].substring(2, name[0].length()));
        }
        //获得连对
        for (int i = 0, len = l.size(); i < len; i++) {
            int k = i;
            //因为传入的list已经按牌的值的大小排序，所以如果两个相邻对子之间的大小差等于索引的值，说明他们就是连续的
            //k记录的是离起始位置最远的连续对子
            for (int j = i; j < len; j++) {
                if (dou[i] - dou[j] == j - i)
                    k = j;
            }
            //如果k和起始距离i的距离相差大于等于2的话就能组成连对
            if (k - i >= 2) {
                String dui = "";
                for (int j = i; j < k; j++) {
                    dui += l.get(j) + ",";
                    del.add(l.get(j));
                }
                dui += l.get(k);
                model.a112233.add(dui);
                del.add(l.get(k));
                i = k;
            }
        }
        //把找到的连对从已有的对子中抽出
        l.removeAll(del);
    }
    //三个
    public static void getThree(ArrayList<Poker> list, Model model) {
        ArrayList<Poker> del = new ArrayList<>();
        for (int i = 0, len = list.size(); i < len; i++) {
            //如果i和i+2一样就说明能组成三对
            if (i + 2 < len && Poker.getValue(list.get(i)) == Poker.getValue(list.get(i + 2))) {
                String san = list.get(i).getName() + ",";
                san += list.get(i + 1).getName() + ",";
                san += list.get(i + 2).getName();
                model.a3.add(san);
                for (int j = i; j <= i + 2; j++)
                    del.add(list.get(j));
                i = i + 2;
            }
        }
        list.removeAll(del);
    }
    //飞机
    public static void getPlane(ArrayList<Poker> list, Model model) {
        ArrayList<String> del = new ArrayList<>();
        ArrayList<String> l = model.a3;
        if (l.size() < 2)
            return;
        Integer plane[] = new Integer[l.size()];
        for (int i = 0, len = l.size(); i < len; i++) {
            String[] name = l.get(i).split(",");
            plane[i] = Integer.parseInt(name[0].substring(2, name[0].length()));
        }
        //因为传入的list已经按牌的值的大小排序，所以如果两个相邻三对之间的大小差等于索引的值，说明他们就是连续的
        //k记录的是离起始位置最远的连续对子
        for (int i = 0, len = l.size(); i < len; i++) {
            int k = i;
            //
            for (int j = i; j < len; j++) {
                if (plane[i] - plane[j] == j - i)
                    k = j;
            }
            //只要k不等于其实位置i，说明有飞机
            if (k != i) {
                String ss = "";
                for (int j = i; j < k; j++) {
                    ss += l.get(j) + ",";
                    del.add(l.get(j));
                }
                ss += l.get(k);
                model.a111222.add(ss);
                del.add(l.get(k));
                i = k;
            }
        }
        l.removeAll(del);
    }
    //找炸弹
    public static void getBoomb(ArrayList<Poker> list, Model model) {
        ArrayList<Poker> del = new ArrayList<>();
        //长度小于一，不会有炸弹
        if (list.size() <= 1)
            return;
        //王炸
        if (list.size() >= 2 && Poker.getColor(list.get(0)) == 5 && Poker.getColor(list.get(1)) == 5) {
            model.a4.add(list.get(0).getName() + "," + list.get(1).getName());
            del.add(list.get(0));
            del.add(list.get(1));
        }
        //如果王只有一张就给单排
        //可省略？
        if (Poker.getColor(list.get(0)) == 5 && Poker.getColor(list.get(1)) != 5) {
            del.add(list.get(0));
            model.a1.add(list.get(0).getName());
        }
        list.removeAll(del);
        //第一张和后面第三张一样就是炸弹
        for (int i = 0, len = list.size(); i < len; i++) {
            if (i + 3 < len && Poker.getValue(list.get(i)) == Poker.getValue(list.get(i + 3))) {
                String s = list.get(i).getName() + ",";
                s += list.get(i + 1).getName() + ",";
                s += list.get(i + 2).getName() + ",";
                s += list.get(i + 3).getName();
                model.a4.add(s);
                for (int j = i; j <= i + 3; j++)
                    del.add(list.get(j));
                i = i + 3;
            }
        }
        list.removeAll(del);
    }
    public static void get123(ArrayList<Poker> list, Model model) {
        ArrayList<Poker> del = new ArrayList<>();
        //当最大的牌不满足组成顺子的时候
        if (list.size() > 0 && (Poker.getValue(list.get(0)) < 7 || Poker.getValue(list.get(list.size() - 1)) > 10))
            return;
        if (list.size() < 5)
            return;

        ArrayList<Poker> temp = new ArrayList<>();
        ArrayList<Integer> integers = new ArrayList<>();
        //把牌都变为单张的存入temp列表中
        for (Poker poker : list) {
            if (integers.indexOf(Poker.getValue(poker)) < 0) {
                integers.add(Poker.getValue(poker));
                temp.add(poker);
            }
        }
        //给存入的temp排序
        Order.order(temp);
        //判断是否存在顺子
        for (int i = 0, len = temp.size(); i < len; i++) {
            int k = i;
            for (int j = i; j < len; j++) {
                if (Poker.getValue(temp.get(i)) - Poker.getValue(temp.get(j)) == j - i) {
                    k = j;
                }
            }
            if (k - i >= 4) {
                String s = "";
                for (int j = i; j < k; j++) {
                    s += temp.get(j).getName() + ",";
                    del.add(temp.get(j));
                }
                s += temp.get(k).getName();
                del.add(temp.get(k));
                model.a123.add(s);
                i = k;
            }
        }
        //将找到的顺子去除
        list.removeAll(del);
    }
    //通过名字找到牌
    public static List getCardByName(List<Poker> list, String n) {
        String[] name = n.split(",");
        ArrayList cardsList = new ArrayList();
        int j = 0;
        for (int i = 0, len = list.size(); i < len; i++) {
            if (j < name.length && list.get(i).getName().equals(name[j])) {
                cardsList.add(list.get(i));
                i = 0;
                j++;
            }
        }
        return cardsList;
    }
    //按照牌的大小序列进行排序
    public static ArrayList getOrder2(List<Poker> list) {
        ArrayList<Poker> list2 = new ArrayList<>(list);
        ArrayList<Poker> list3 = new ArrayList<>();
        int len = list2.size();
        int a[] = new int[20];
        for (int i = 0; i < 20; i++)
            a[i] = 0;
        //计算每种牌出现的次数
        for (int i = 0; i < len; i++) {
            a[Poker.getValue(list2.get(i))]++;
        }
        int max = 0;
        //把出现次数最多的牌按照大小顺序放在前面
        for (int i = 0; i < 20; i++) {
            max = 0;
            for (int j = 19; j >= 0; j--) {
                if (a[j] > a[max])
                    max = j;
            }

            for (int k = 0; k < len; k++) {
                if (Poker.getValue(list2.get(k)) == max) {
                    list3.add(list2.get(k));
                }
            }
            //移除掉已经添加的牌
            list2.remove(list3);
            a[max] = 0;
        }
        return list3;
    }

    //仅仅通过来自model的字符串获得牌的大小
    public static int getValueInt(String n) {
        //将字符串根据","切分
        String name[] = n.split(",");
        //牌列中第一个牌是决定整个牌大小的
        String s = name[0];
        //计算value
        int i = Integer.parseInt(s.substring(2, s.length()));
        if (s.substring(0, 1).equals("5"))
            i += 3;
        if (s.substring(2, s.length()).equals("1") || s.substring(2, s.length()).equals("2"))
            i += 13;
        return i;
    }

    //Computer_1是只需通过一种model就可以判断大小的牌型 如单对三炸弹
    //model是传入的牌 player为上个玩家出的牌 list是用来接受函数的结果
    public static void Computer_1(List<String> model, List<Poker> player, List<String> list, int role) {
        //查找到第一个比player出的大的poker
        for (int len = model.size(), i = len - 1; i >= 0; i--) {
            if (getValueInt(model.get(i)) > Poker.getValue(player.get(0))) {
                list.add(model.get(i));
                break;
            }
        }
    }
    //三带一 三带二
    public static void Computer_2(List<String> model1, List<String> model2, List<Poker> player, List<String> list, int role) {
        //将要出的牌型
        player = getOrder2(player);

        int len1 = model1.size();
        int len2 = model2.size();

        if (len1 < 1 || len2 < 1)
            return;

        //找到第一个比player大的
        for (int len = len1, i = len - 1; i >= 0; i--) {
            if (getValueInt(model1.get(i)) > Poker.getValue(player.get(0))) {
                list.add(model1.get(i));
                break;
            }
        }
        //想list中添加最小的不影响比较大小的牌
        list.add(model2.get(len2 - 1));
        if (list.size() < 2)
            list.clear();
    }
    //顺子 连对 纯飞机
    public static void Computer_3(List<String> model, List<Poker> player, List<String> list, int role) {

        for (int i = 0, len = model.size(); i < len; i++) {
            //将model中的字符串通过","分割
            String[] card = model.get(i).split(",");
            //判断顺子的长度和不和上个玩家出的牌的长度相等
            if (card.length == player.size() && getValueInt(model.get(i)) > Poker.getValue(player.get(0))) {
                list.add(model.get(i));
                return;
            }
        }
    }
    //四带单 四带对
    public static void Computer_4(List<String> model1, List<String> model2, List<Poker> player, List<String> list, int role) {
        player = getOrder2(player);
        //获取能出model的长度
        int len1 = model1.size();
        int len2 = model2.size();
        //不能组成时直接返回
        if (len1 < 1 || len2 < 2)
            return;
        //找到第一个比他大的
        for (int i = 0; i < len1; i++) {
            if (getValueInt(model1.get(i)) > Poker.getValue(player.get(0))) {
                list.add(model1.get(i));
                for (int j = 1; j <= 2; j++)
                    list.add(model2.get(len2 - j));
                return;
            }
        }
    }
    //飞机
    public static void Computer_5(List<String> model1, List<String> model2, List<Poker> player, List<String> list, int role) {
        player = getOrder2(player);
        //获得model中可出的长度
        int len1 = model1.size();
        int len2 = model2.size();

        if (len1 < 1 || len2 < 1)
            return;
        for (int i = 0; i < len1; i++) {
            String[] s = model1.get(i).split(",");
            String[] s2 = model2.get(0).split(",");
            //如果翅膀可出的种类小于飞机的长度/3的话就不可出
            if ((s.length / 3 <= len2) && (s.length * (3 + s2.length) == player.size())
                    && getValueInt(model1.get(i)) > Poker.getValue(player.get(0))) {
                list.add(model1.get(i));
                for (int j = 1; j <= s.length / 3; j++)
                    list.add(model2.get(len2 - j));
                return;
            }
        }
    }
}

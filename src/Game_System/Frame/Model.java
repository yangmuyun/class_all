package Game_System.Frame;

import java.util.ArrayList;

public class Model {
    int value; //权值
    int num;// 手数 (几次能够走完，没有挡的情况下)
    ArrayList<String> a1=new ArrayList<>(); //单张
    ArrayList<String> a2=new ArrayList<>(); //对子
    ArrayList<String> a3=new ArrayList<>(); //3带
    ArrayList<String> a123=new ArrayList<>(); //连子
    ArrayList<String> a112233=new ArrayList<>(); //连牌
    ArrayList<String> a111222=new ArrayList<>(); //飞机
    ArrayList<String> a4=new ArrayList<>(); //炸弹

    public String toString() {
        return "Model{" +
                "value=" + value +
                ", num=" + num +
                ", a1=" + a1 +
                ", a2=" + a2 +
                ", a3=" + a3 +
                ", a123=" + a123 +
                ", a112233=" + a112233 +
                ", a111222=" + a111222 +
                ", a4=" + a4 +
                '}';
    }
}

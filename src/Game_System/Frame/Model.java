package Game_System.Frame;

import java.util.ArrayList;

public class Model {
    int value; //Ȩֵ
    int num;// ���� (�����ܹ����꣬û�е��������)
    ArrayList<String> a1=new ArrayList<>(); //����
    ArrayList<String> a2=new ArrayList<>(); //����
    ArrayList<String> a3=new ArrayList<>(); //3��
    ArrayList<String> a123=new ArrayList<>(); //����
    ArrayList<String> a112233=new ArrayList<>(); //����
    ArrayList<String> a111222=new ArrayList<>(); //�ɻ�
    ArrayList<String> a4=new ArrayList<>(); //ը��

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

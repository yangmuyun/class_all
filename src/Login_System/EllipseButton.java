package Login_System;

import javax.swing.*;
import java.awt.*;
import java.awt.geom.Ellipse2D;

public class EllipseButton extends JButton {
    public EllipseButton(String label) {
        super(label);
        setContentAreaFilled(false);
    }

    @Override
    protected void paintComponent(Graphics g) {
        if (getModel().isArmed()) {
            g.setColor(Color.lightGray);
        } else {
            g.setColor(Color.BLUE);
        }
        g.fillOval(0, 0, getSize().width-1, getSize().height-1);
        super.paintComponent(g);
    }

    @Override
    protected void paintBorder(Graphics g) {
        g.setColor(getForeground());
        g.drawOval(0, 0, getSize().width-1, getSize().height-1);
    }
    Shape shape;

    @Override
    public boolean contains(int x, int y) {
        if (shape == null || !shape.getBounds().equals(getBounds())) {
            shape = new Ellipse2D.Float(0, 0, getWidth(), getHeight());
        }
        return shape.contains(x, y);
    }
    public static void main(String[] args) {
        JFrame frame = new JFrame("Ellipse Button Demo");
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setSize(200, 200);
        EllipseButton button = new EllipseButton("Click Me!");
        button.setPreferredSize(new Dimension(100, 100));
        frame.getContentPane().setLayout(new FlowLayout());
        frame.getContentPane().add(button);
        frame.setVisible(true);    }
}
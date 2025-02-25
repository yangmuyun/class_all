import time
import random
import cv2
from Arm_Lib import Arm_Device


class robot_DiceGame:
    def __init__(self):
        self.Arm = Arm_Device()
        time.sleep(0.1)

        # 定义机械臂动作时间
        self.time_1 = 500
        self.time_sleep = 0.5

        # 初始化游戏参数
        self.board_length = 13
        self.player1_position = -1  # 玩家1的初始位置
        self.player2_position = -1  # 玩家2的初始位置
        self.dice_count1 = 0  # 玩家1的投骰子次数
        self.dice_count2 = 0  # 玩家2的投骰子次数

        # 初始化机械臂动作
        self.init_arm_positions()
        self.cap = None

        self.p1 = [5, 75, 20, 0, 90]  # p0 = [90, 90, 45, 0, 90]
        self.p2 = [5, 60, 40, 10, 90]
        self.change = 13.2
        self.p1_1 = [50, 18, 97, 20, 90]
        self.p2_2 = [70, 18, 97, 20, 90]

    # 初始化机械臂位置
    def init_arm_positions(self):
        self.Arm.Arm_serial_servo_write6(90, 90, 90, 90, 90, 90, 500)
        time.sleep(1)

    # 定义夹取积木块函数，enable=1：夹住，=0：松开
    def arm_clamp_block(self, enable):
        if enable == 0:
            self.Arm.Arm_serial_servo_write(6, 40, 1000)
        else:
            self.Arm.Arm_serial_servo_write(6, 150, 400)
        time.sleep(0.5)

    # 定义移动机械臂函数，同时控制 1-5 号舵机运动，p=[S1,S2,S3,S4,S5]
    def arm_move(self, p, s_time=500):
        for i in range(5):
            id = i + 1
            self.Arm.Arm_serial_servo_write(id, p[i], s_time)
            time.sleep(0.01)
        time.sleep(s_time / 1000)

    # 机械臂向上移动1
    def arm_move_up1(self):
        self.Arm.Arm_serial_servo_write(2, 90, 1500)
        time.sleep(0.1)

    # 机械臂向上移动2
    def arm_move_up2(self):
        self.Arm.Arm_serial_servo_write(2, 75, 1500)
        time.sleep(0.1)

    # 机械臂向上移动0_1
    def arm_move_0_1(self):
        p = [5, 107, 9, 0, 90]
        self.Arm.Arm_serial_servo_write(2, 90, 1000)
        time.sleep(0.1)
        self.Arm.Arm_serial_servo_write(3, 45, 1000)
        time.sleep(0.1)
        self.arm_move(p, 1000)
        time.sleep(0.1)

    # 机械臂向上移动0_2
    def arm_move_0_2(self):
        p = [5, 90, 24, 7, 90]
        self.Arm.Arm_serial_servo_write(2, 90, 1000)
        time.sleep(0.1)
        self.Arm.Arm_serial_servo_write(3, 45, 1000)
        time.sleep(0.1)
        self.arm_move(p, 1000)
        time.sleep(0.1)

    # 识别颜色并返回相应的数字
    def getdice_num(self):
        # 打开摄像头
        # while True:
        #     last_num = None
        #     current_num = None
        #
        #     while current_num == last_num:
        #         current_num = random.randint(0, 2)
        #
        #     cap = cv2.VideoCapture(current_num)
        #     if cap.isOpened():
        #         print("Camera ok")
        #         break
        #     print(current_num)
        #     print("Error: Could not open camera.")
        #     last_num = current_num
        # if cap.isOpened():
        #     print("Error: Could not open camera.1 can not")
        #     num=2

        # return -1  # 返回-1表示摄像头打开失败

        # if not cap.isOpened():
        #     print("Error: Could not open camera.")
        #     return -1  # 返回-1表示摄像头打开失败

        # 读取一帧图像
        #         cap = cv2.VideoCapture(1)
        #         if not cap.isOpened():
        #             print("Error: Could not open camera.")
        #             return -1  # 返回-1表示摄像头打开失败

        #         # 读取一帧图像
        #         ret, frame = cap.read()
        #         cap.release()  # 读取一帧图像后立即释放摄像头
        #         if not ret:
        #             print("Error: Could not read frame.")
        #             return -1  # 返回-1表示读取图像失败
        # #         cap=self.cap
        # #         ret, frame = cap.read()
        #         cap.release()
        #         cap = cv2.VideoCapture(1)
        cap = self.cap
        if not cap.isOpened():
            print("Error: Could not open camera.")
            return -1  # 返回-1表示摄像头打开失败

        # 读取一帧图像
        ret, frame = cap.read()
        cv2.imshow("image", frame)
        #         cap.release()  # 读取一帧图像后立即释放摄像头
        if not ret:
            print("Error: Could not read frame.")
            return -1  # 返回-1表示读取图像失败
        #         cap=self.cap
        #         ret, frame = cap.read()
        #         cap.release()
        height, width, _ = frame.shape

        # 定义中心区域的尺寸（例如：50x50像素）
        center_size = 50
        x_start = width // 2 - center_size // 2
        y_start = height // 2 - center_size // 2
        x_end = x_start + center_size
        y_end = y_start + center_size

        # 获取中心区域图像
        center_region = frame[y_start:y_end, x_start:x_end]

        # 将中心区域图像转换为HSV颜色空间
        hsv = cv2.cvtColor(center_region, cv2.COLOR_BGR2HSV)

        # 定义颜色的HSV范围
        color_ranges = {
            1: [(160, 100, 170), (175, 200, 240)],  # 红色
            2: [(100, 150, 140), (140, 255, 255)],  # 蓝色
            3: [(75, 200, 100), (90, 255, 180)],  # 绿色
            4: [(25, 100, 190), (32, 180, 255)],  # 黄色
            5: [(95, 130, 35), (120, 218, 65)],  # 黑色
            6: [(80, 5, 100), (125, 200, 255)]  # 白色
        }
        max_pixels = 0
        detected_dice_num = -1

        # 定义颜色与数字的映射
        mapping = {
            1: "red",
            2: "blue",
            3: "green",
            4: "yellow",
            5: "black",
            6: "white"
        }

        for dice_num, ranges in color_ranges.items():
            mask = cv2.inRange(hsv, ranges[0], ranges[1])

            pixels = cv2.countNonZero(mask)
            if pixels > max_pixels:
                max_pixels = pixels
                detected_dice_num = dice_num

        if detected_dice_num == -1:
            print("camera error")
        else:
            print(f"the color is: {mapping[detected_dice_num]} the number is: {detected_dice_num}")

        # 返回检测到的数值
        return detected_dice_num

    def throw_dice(self):
        # 机械臂移动到拿骰子的位置
        pick_position = [90, 46, 53, 22, 90]
        p_vidio = [90, 78, 24, 13, 90]

        self.Arm.Arm_serial_servo_write(2, 90, 1500)  # 抬起
        time.sleep(1)
        self.arm_move(pick_position, 1000)
        self.arm_clamp_block(1)
        time.sleep(1)
        self.Arm.Arm_serial_servo_write(2, 90, 1500)  # 抬起
        time.sleep(1)
        self.arm_clamp_block(0)
        time.sleep(1)
        self.Arm.Arm_serial_servo_write(5, 90, 500)
        time.sleep(0.1)

        # 获取投掷结果
        self.arm_move(p_vidio, 1000)
        time.sleep(3)
        result = self.getdice_num()

        # 如果识别失败则重新投掷
        while result == -1:
            print("Retrying dice throw...")
            result = self.getdice_num()
        return result

    # 投骰子，返回1到6的随机数
    def roll_dice(self):
        return self.throw_dice()

    # 定义不同位置的变量参数

    def position_move(self, po1, po2):
        self.arm_move(po1, 1000)
        self.arm_clamp_block(1)
        time.sleep(1)
        self.arm_move_up1()
        time.sleep(1)
        self.arm_move(po2, 1000)
        self.arm_clamp_block(0)
        time.sleep(1)

    def position_move2(self, p, steps):
        self.arm_move(p, 1000)
        self.arm_clamp_block(1)
        time.sleep(1)
        self.arm_move_up1()
        time.sleep(1)
        p[0] += self.change * steps
        self.arm_move(p, 1000)
        self.arm_clamp_block(0)
        time.sleep(1)

    def position_move0_1(self, po1, po2):
        self.arm_move(po1, 1000)
        self.arm_clamp_block(1)
        time.sleep(1)
        self.arm_move_0_1()
        time.sleep(1)
        self.arm_move(po2, 1000)
        self.arm_clamp_block(0)
        time.sleep(1)

    def position_move0_2(self, po1, po2):
        self.arm_move(po1, 1000)
        self.arm_clamp_block(1)
        time.sleep(1)
        self.arm_move_0_2()
        time.sleep(1)
        self.arm_move(po2, 1000)
        self.arm_clamp_block(0)
        time.sleep(1)

    def move_player(self, player, steps):
        if player == 1:
            if self.player1_position == -1:  # 棋子未在棋盘上
                if steps == 6 or self.dice_count1 == 2:
                    self.player1_position = 0  # 将棋子放到初始位置
                    self.position_move0_1(self.p1_1, self.p1)
                    print(f"Player {player} places the piece at the starting position.")
                    self.dice_count1 = 0
                    print(f"the dice_number is dice_count1={self.dice_count1}")
                else:
                    print(f"Player {player} needs to roll a 6 to start.")
                    print(f"the dice_number is dice_count1={self.dice_count1}")
                    self.dice_count1 += 1
                    print(f"the dice_number is dice_count1={self.dice_count1}")
                    return False
            else:
                old_player1_position = self.player1_position
                self.player1_position += steps
                if self.player1_position > self.board_length:
                    steps = self.board_length - old_player1_position
                    self.player1_position = self.board_length
                print(f"Player {player} moves to position {self.player1_position}")
                self.position_move2(self.p1, steps)
                if self.player1_position == self.board_length:
                    b_time = 3
                    self.Arm.Arm_Buzzer_On(b_time)
                    print(f"Player {player} wins!")
                    return True
        elif player == 2:
            if self.player2_position == -1:  # 棋子未在棋盘上
                if steps == 6 or self.dice_count2 == 2:
                    self.player2_position = 0  # 将棋子放到初始位置
                    self.position_move0_2(self.p2_2, self.p2)
                    print(f"Player {player} places the piece at the starting position.")
                    self.dice_count2 = 0
                    print(f"the dice_number is dice_count2={self.dice_count2}")
                else:
                    print(f"Player {player} needs to roll a 6 to start.")
                    print(f"the dice_number is dice_count2={self.dice_count2}")
                    self.dice_count2 += 1
                    print(f"the dice_number is dice_count2={self.dice_count2}")
                    return False
            else:
                old_player2_position = self.player2_position
                self.player2_position += steps
                if self.player2_position > self.board_length:
                    steps = self.board_length - old_player2_position
                    self.player2_position = self.board_length
                print(f"Player {player} moves to position {self.player2_position}")
                self.position_move2(self.p2, steps)
                if self.player2_position == self.board_length:
                    b_time = 3
                    self.Arm.Arm_Buzzer_On(b_time)
                    print(f"Player {player} wins!")
                    return True
        else:
            print(f"Unknown player: {player}")
            return False
        return False

    # 游戏主循环
    def main_game(self):
        current_player = 1

        while True:
            print(f"Player {current_player}'s turn:")
            print("rolling dice...")
            steps = self.roll_dice()
            print(f"Player {current_player} rolls {steps}")

            if self.move_player(current_player, steps):
                break

            # 切换玩家
            current_player = 2 if current_player == 1 else 1

    def start_robot_game(self):
        try:
            self.main_game()
        except KeyboardInterrupt:
            print("\nProgram closed!")
        finally:
            guiwei = [90, 90, 90, 90, 90, 90, 500]
            self.arm_move(guiwei, 1000)
            time.sleep(1)
            # 如果有关闭连接的方法，可以在这里使用
            # self.Arm.disconnect()  # 假设 Arm_Device 没有 disconnect 方法


# 创建游戏对象并运行
if __name__ == "__main__":
    game = robot_DiceGame()
    game.start_robot_game()



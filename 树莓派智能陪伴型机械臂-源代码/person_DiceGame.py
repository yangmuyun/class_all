import time
import random
import cv2
from Arm_Lib import Arm_Device  # Assuming Arm_Device is the library for your robotic arm
from speak import speak


class person_DiceGame:
    def __init__(self):
        # Create the Arm_Device object for controlling the robotic arm
        self.Arm = Arm_Device()
        time.sleep(0.1)

        # Define arm action times
        self.time_1 = 1000
        self.time_sleep = 0.5

        # Initialize game parameters
        self.board_length = 13
        self.player1_position = -1  # Player 1's initial position
        self.player2_position = -1  # Player 2's initial position
        self.dice_count1 = 0  # Player 1's dice roll count
        self.dice_count2 = 0  # Player 2's dice roll count
        self.p1 = [5, 75, 20, 0, 90]
        self.p2 = [5, 60, 40, 10, 90]
        self.change = 13.2
        self.p1_1 = [50, 18, 97, 20, 90]
        self.p2_2 = [70, 18, 97, 20, 90]
        # Initialize arm positions
        self.init_arm_positions()
        self.cap = None

    def init_arm_positions(self):
        self.Arm.Arm_serial_servo_write6(90, 90, 90, 90, 90, 90, 500)
        time.sleep(1)

    def arm_clamp_block(self, enable):
        if enable == 0:
            self.Arm.Arm_serial_servo_write(6, 0, 400)
        else:
            self.Arm.Arm_serial_servo_write(6, 150, 400)
        time.sleep(0.5)

    def arm_move(self, p, s_time=500):
        for i in range(5):
            id = i + 1
            self.Arm.Arm_serial_servo_write(id, p[i], s_time)
            time.sleep(0.01)
        time.sleep(s_time / 1000)

    def arm_move_up1(self):
        self.Arm.Arm_serial_servo_write(2, 90, 1500)
        time.sleep(0.1)

    def arm_move_0_1(self):
        p = [5, 107, 9, 0, 90]
        self.Arm.Arm_serial_servo_write(2, 90, 1000)
        time.sleep(0.1)
        self.Arm.Arm_serial_servo_write(3, 45, 1000)
        time.sleep(0.1)
        self.arm_move(p, 1000)
        time.sleep(0.1)

    def arm_move_up2(self):
        self.Arm.Arm_serial_servo_write(2, 75, 1500)
        time.sleep(0.1)

    def getdice_num(self):
#         cap = cv2.VideoCapture(1)
#         if not cap.isOpened():
#             print("Error: Could not open camera.")
#             return -1  # 返回-1表示摄像头打开失败

        cap=self.cap
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

        center_size = 40
        x_start = width // 2 - center_size // 2
        y_start = height // 2 - center_size // 2
        x_end = x_start + center_size
        y_end = y_start + center_size

        center_region = frame[y_start:y_end, x_start:x_end]
        print(f"center_region: {center_region}")

        hsv = cv2.cvtColor(center_region, cv2.COLOR_BGR2HSV)

        color_ranges = {
            1: [(0, 126, 191), (181, 253, 255)],  # Red
            2: [(104, 150, 160), (113, 253, 255)],  # Blue
            3: [(57, 76, 111), (75, 253, 255)],  # Green
            4: [(28, 63, 240), (32, 253, 255)],  # Yellow
            5: [(0, 0, 0), (120, 218, 65)],  # Black
            6: [(80, 5, 100), (125, 200, 255)]  # White
        }
        max_pixels = 0
        detected_dice_num = -1

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
            detected_hsv = cv2.bitwise_and(hsv, hsv, mask=mask)
            print(f"Detected HSV values: {detected_hsv}")
            if pixels > max_pixels:
                max_pixels = pixels
                detected_dice_num = dice_num

        if detected_dice_num == -1:
            print("Camera error")
        else:
            print(f"The color is: {mapping[detected_dice_num]}, the number is: {detected_dice_num}")

        return detected_dice_num

    Reader_Type = {
        'Reader_XiaoYan': 3,  # 为 3，设置发音人为小燕(女声, 推荐发音人)
        'Reader_XuJiu': 51,  # 为 51，设置发音人为许久(男声, 推荐发音人)
        'Reader_XuDuo': 52,  # 为 52，设置发音人为许多(男声)
        'Reader_XiaoPing': 53,  # 为 53，设置发音人为小萍(女声
        'Reader_DonaldDuck': 54,  # 为 54，设置发音人为唐老鸭(效果器)
        'Reader_XuXiaoBao': 55  # 为 55，设置发音人为许小宝(女童声)
    }  # 选择发音人 [m?]'

    def person_speak(self, num):
        myspeak = speak()
        if num == 1:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
            myspeak.Speech_text("请小主人走1步", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 2:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
            myspeak.Speech_text("请小主人走2步", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 3:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
            myspeak.Speech_text("请小主人走3步", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 4:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
            myspeak.Speech_text("请小主人走4步", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 5:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
            myspeak.Speech_text("请小主人走5步", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 6:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
            myspeak.Speech_text("请小主人走6步", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)

    def throw_dice(self):
        pick_position = [90, 46, 53, 22, 90]
        p_vidio = [90, 78, 24, 13, 90]

        self.Arm.Arm_serial_servo_write(2, 90, 1500)  # Lift
        time.sleep(1)
        self.arm_move(pick_position, 1000)
        self.arm_clamp_block(1)
        time.sleep(1)
        self.Arm.Arm_serial_servo_write(2, 90, 1500)  # Lift
        time.sleep(1)
        self.arm_clamp_block(0)
        time.sleep(1)
        self.Arm.Arm_serial_servo_write(5, 90, 500)
        time.sleep(0.1)

        self.arm_move(p_vidio, 1000)
        time.sleep(3)
        result = self.getdice_num()

        while result == -1:
            print("Retrying dice throw...")
            result = self.getdice_num()

        return result

    def roll_dice(self):
        return self.throw_dice()

    def fail(self):
        guiwei = [90, 90, 90, 90, 90, 90, 500]
        self.arm_move(guiwei)
        time.sleep(1)
        p_fail = [90, 90, 15, 5, 90]
        self.arm_move(p_fail)
        self.arm_clamp_block(1)
        time.sleep(1)
        self.arm_clamp_block(0)

    def win(self):
        guiwei = [90, 90, 90, 90, 90, 90, 500]
        times = 0.003
        self.arm_move(guiwei)
        time.sleep(1)
        self.Arm.Arm_serial_servo_write(2, 180 - 120, self.time_1)
        time.sleep(times)
        self.Arm.Arm_serial_servo_write(3, 120, self.time_1)
        time.sleep(times)
        self.Arm.Arm_serial_servo_write(4, 60, self.time_1)
        time.sleep(self.time_sleep)
        self.Arm.Arm_serial_servo_write(2, 180 - 135, self.time_1)
        time.sleep(times)
        self.Arm.Arm_serial_servo_write(3, 135, self.time_1)
        time.sleep(times)
        self.Arm.Arm_serial_servo_write(4, 45, self.time_1)
        time.sleep(self.time_sleep)
        self.Arm.Arm_serial_servo_write(2, 180 - 120, self.time_1)
        time.sleep(times)
        self.Arm.Arm_serial_servo_write(3, 120, self.time_1)
        time.sleep(times)
        self.Arm.Arm_serial_servo_write(4, 60, self.time_1)
        time.sleep(self.time_sleep)

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

    def move_player(self, player, steps):
        if player == 1:
            if self.player1_position == -1:  # Piece not on board
                if steps == 6 or self.dice_count1 == 2:
                    self.player1_position = 0  # Place piece at start position
                    self.position_move0_1(self.p1_1, self.p1)
                    print(f"Player {player} places the piece at the starting position.")
                    self.dice_count1 = 0
                    print(f"Dice count for Player 1: {self.dice_count1}")
                else:
                    print(f"Player {player} needs to roll a 6 to start.")
                    self.dice_count1 += 1
                    print(f"Dice count for Player 1: {self.dice_count1}")
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
                    print(f"Player {player} wins!")
                    self.win()
                    b_time = 6
                    self.Arm.Arm_Buzzer_On(b_time)
                    return True
        elif player == 2:
            print("Player 2's turn (human player):")
            print("Rolling dice...")

            b_time = 7
            self.Arm.Arm_Buzzer_On(b_time)
            time.sleep(3)
            p_vidio = [90, 78, 24, 13, 90]
            self.arm_move(p_vidio, 1000)
            time.sleep(3)
            steps = self.getdice_num()

            while steps == -1:
                print("Retrying dice throw...")
                steps = self.getdice_num()

            print(f"Player {player} rolls {steps}")

            if self.player2_position == -1:  # Piece not on board
                if steps == 6 or self.dice_count2 == 2:
                    self.player2_position = 0  # Place piece at start position
                    print(f"Player {player} places the piece at the starting position.")
                    self.dice_count2 = 0
                    print(f"Dice count for Player 2: {self.dice_count2}")
                else:
                    print(f"Player {player} needs to roll a 6 to start.")
                    self.dice_count2 += 1
                    print(f"Dice count for Player 2: {self.dice_count2}")
                    return False
            else:
                old_player2_position = self.player2_position
                self.player2_position += steps
                if self.player2_position > self.board_length:
                    steps = self.board_length - old_player2_position
                    self.player2_position = self.board_length
                print(f"Player {player} moves to position {self.player2_position}")
                if self.player2_position == self.board_length:
                    print(f"Player {player} wins!")
                    self.fail()
                    b_time = 3
                    self.Arm.Arm_Buzzer_On(b_time)
                    return True

        else:
            print(f"Unknown player: {player}")
            return False
        return False

    def start_person_game(self):
        current_player = 1
        try:
            while True:
                print(f"Player {current_player}'s turn:")
                print("Rolling dice...")

                if current_player == 1:
                    steps = self.roll_dice()
                    print(f"Player {current_player} rolls {steps}")
                else:
                    print("Player 2's turn (human player):")

                    b_time = 7
                    self.Arm.Arm_Buzzer_On(b_time)
                    time.sleep(3)
                    p_vidio = [90, 78, 24, 13, 90]
                    self.arm_move(p_vidio, 1000)
                    time.sleep(3)
                    steps = self.getdice_num()
                    self.person_speak(steps)

                    while steps == -1:
                        print("Retrying dice throw...")
                        steps = self.getdice_num()

                    print(f"Player {current_player} rolls {steps}")

                if self.move_player(current_player, steps):
                    break

                current_player = 2 if current_player == 1 else 1
        except KeyboardInterrupt:
            cap.release()
            print("\nProgram closed!")
        finally:
            guiwei = [90, 90, 90, 90, 90, 90, 500]
            game.arm_move(guiwei, 1000)
            time.sleep(1)


if __name__ == "__main__":
    try:
        game = person_DiceGame()
        game.start_person_game()
    except KeyboardInterrupt:
        print("\nProgram closed!")
    finally:
        guiwei = [90, 90, 90, 90, 90, 90, 500]
        game.arm_move(guiwei, 1000)
        time.sleep(1)

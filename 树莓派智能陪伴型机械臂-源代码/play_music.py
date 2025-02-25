#!/usr/bin/env python3
#coding=utf-8
import time
from Arm_Lib import Arm_Device
import smbus
import time
from speak import speak

class RoboticArmController:
    def __init__(self):
        self.arm = Arm_Device()  # Initialize the robotic arm
        self.init_arm_positions()  # Initialize arm to default position
        self.time_sleep = 0.5  # Define default sleep time
        self.bus = smbus.SMBus(1)

        self.seki2c_addr = 0x30
        self.date_head = 0xfd
        global i2c_addr
        # Define arm positions for different notes
        self.positions = {
            0: [160, 140, 20, 25, 89],
            1: [63, 129, 25, 40, 180],
            2: [71, 138, 13, 40, 180],
            3: [77, 140, 13, 40, 180],
            4: [83, 148, 6, 40, 180],
            5: [89, 148, 5, 40, 180],
            6: [96, 148, 5, 40, 180],
            7: [100, 148, 5, 40, 180],
            8: [106, 148, 5, 40, 180],
        }

    Reader_Type = {
        'Reader_XiaoYan': 3,  # 为 3，设置发音人为小燕(女声, 推荐发音人)
        'Reader_XuJiu': 51,  # 为 51，设置发音人为许久(男声, 推荐发音人)
        'Reader_XuDuo': 52,  # 为 52，设置发音人为许多(男声)
        'Reader_XiaoPing': 53,  # 为 53，设置发音人为小萍(女声
        'Reader_DonaldDuck': 54,  # 为 54，设置发音人为唐老鸭(效果器)
        'Reader_XuXiaoBao': 55  # 为 55，设置发音人为许小宝(女童声)
    }  # 选择发音人 [m?]'

    def init_arm_positions(self):
        self.arm.Arm_serial_servo_write6(90, 143, 20, 25, 89, 90, 500)
        time.sleep(1)

    def arm_clamp_block(self, enable):
        if enable == 0:
            self.arm.Arm_serial_servo_write(6, 40, 400)
        else:
            self.arm.Arm_serial_servo_write(6, 180, 400)
        time.sleep(self.time_sleep)


    def arm_move(self, p, s_time=500):
        for i in range(5):
            id = i + 1
            self.arm.Arm_serial_servo_write(id, p[i], s_time)
            time.sleep(0.01)
        time.sleep(s_time / 1000)

    def arm_qiao(self, enable):
        if enable == 0:
            self.arm.Arm_serial_servo_write(4, 30, 200)  # Lift
        else:
            self.arm.Arm_serial_servo_write(4, 0, 100)  # Strike
        time.sleep(self.time_sleep)

    def arm_qiaoji(self):
        time.sleep(0.01)
        self.arm_qiao(1)
        time.sleep(1)
        self.arm_qiao(0)
        time.sleep(0.01)

    def arm_return(self):
        c_time = 2
        self.arm.Arm_Buzzer_On(c_time)
        self.arm_move(self.positions[0], 500)
        self.arm_clamp_block(0)

    def person_speak(self, num):
        myspeak=speak()
        if num == 1:
            myspeak.SetReader(myspeak.Reader_Type["Reader_DonaldDuck"])  # 选择播音人许多
            myspeak.Speech_text("哆", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 2:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XuJiu"])  # 选择播音人许多
            myspeak.Speech_text("来", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 3:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XuDuo"])  # 选择播音人许多
            myspeak.Speech_text("咪", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 4:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
            myspeak.Speech_text("发", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 5:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
            myspeak.Speech_text("嗦", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 6:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoPing"])  # 选择播音人许多
            myspeak.Speech_text("拉", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 7:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoPing"])  # 选择播音人许多
            myspeak.Speech_text("西", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
        elif num == 8:
            myspeak.SetReader(myspeak.Reader_Type["Reader_XuXiaoBao"])  # 选择播音人许多
            myspeak.Speech_text("do", myspeak.EncodingFormat_Type["GB2312"])
            while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                time.sleep(0.1)
    def main_game(self):
        myspeak=speak()
        try:
            # Initialize
            self.init_arm_positions()

            # Grab hammer
            b_time = 10
            self.arm.Arm_Buzzer_On(b_time)
            time.sleep(4)
            a_time = 3
            self.arm.Arm_Buzzer_On(a_time)
            self.arm_clamp_block(1)
            time.sleep(2)
            self.arm.Arm_serial_servo_write(5, 180, 400)
            time.sleep(3)

            # Play musical sequence
            for note in q1:
                if note in self.positions:
                    self.arm_move(self.positions[note], 300)
                    time.sleep(0.01)
                    self.arm_qiaoji()
                    self.person_speak(note)
                    while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                        time.sleep(0.1)
                    print(note)
                else:
                    print(f"Note {note} is not mapped to any position.")

            time.sleep(0.01)
            self.arm_return()

        except KeyboardInterrupt:
            print("\nProgram closed!")

        finally:
            # Reset arm to default position
            guiwei = [90, 90, 90, 90, 90, 90, 500]
            self.arm_move(guiwei, 1000)
            time.sleep(1)
            # Clean up or disconnect arm if applicable
            # self.arm.disconnect()  # Uncomment this line if there's a disconnect method


# Define your musical sequence
q1 = [1, 1, 5, 5, 6, 6, 5, 4, 4, 3, 3, 2, 2, 1, 5, 5, 4, 4, 3, 3, 2, 5, 5, 4, 4, 3, 3, 2]

# Main program execution
if __name__ == "__main__":
    arm_controller = RoboticArmController()
    arm_controller.main_game()
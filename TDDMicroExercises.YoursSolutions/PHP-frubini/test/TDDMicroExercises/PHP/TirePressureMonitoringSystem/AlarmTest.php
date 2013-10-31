<?php
namespace TDDMicroExercises\PHP\TirePressureMonitoringSystem;
/**
 * Class AlarmTest
 * @package TDDMicroExercises\PHP\TirePressureMonitoringSystem
 */
class AlarmTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Alarm
     */
    protected $alarm;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $sensor;

    public function setUp()
    {
        $this->sensor = $this->getMock(
            'TDDMicroExercises\PHP\TirePressureMonitoringSystem\Sensor',
            array('popNextPressurePsiValue')
        );

        $this->alarm = new Alarm($this->sensor);

    }

    /**
     * test the alarm is not triggered between the threshold boundaries
     */
    public function testTheAlarmIsNotTriggeredForMinAndMaxPressureTreshold()
    {
        $this->assertFalse($this->alarm->alarmOn());

        $this->sensor->expects($this->any())
            ->method('popNextPressurePsiValue')
            ->will($this->returnValue(Alarm::LOW_PRESSURE_TRESHOLD));

        $this->alarm->check();
        $this->assertFalse($this->alarm->alarmOn());

        $this->sensor->expects($this->any())
            ->method('popNextPressurePsiValue')
            ->will($this->returnValue(Alarm::HIGH_PRESSURE_TRESHOLD));

        $this->alarm->check();
        $this->assertFalse($this->alarm->alarmOn());
    }

    /**
     * Test the alarm is triggered when the pressure is lower or higher of the threshold values
     */
    public function testTheAlarmTriggeredForPressuresOutsideTheTrashold()
    {
        $this->assertFalse($this->alarm->alarmOn());

        $this->sensor->expects($this->any())
            ->method('popNextPressurePsiValue')
            ->will($this->returnValue(Alarm::LOW_PRESSURE_TRESHOLD - 1));

        $this->alarm->check();
        $this->assertTrue($this->alarm->alarmOn());

        $this->sensor->expects($this->any())
            ->method('popNextPressurePsiValue')
            ->will($this->returnValue(Alarm::HIGH_PRESSURE_TRESHOLD + 1));

        $this->alarm->check();
        $this->assertTrue($this->alarm->alarmOn());
    }


    /**
     * Test that the alarm is triggered when the pressure return to normal
     * if it was previously outside the threshold boundaries.
     */
    public function testNormalPressureValueAfterAnAlarmShouldKeepAlarmOn()
    {
        $this->assertFalse($this->alarm->alarmOn());

        $this->sensor->expects($this->any())
            ->method('popNextPressurePsiValue')
            ->will($this->returnValue(Alarm::LOW_PRESSURE_TRESHOLD - 1));

        $this->alarm->check();
        $this->assertTrue($this->alarm->alarmOn());

        $this->sensor->expects($this->any())
            ->method('popNextPressurePsiValue')
            ->will($this->returnValue(Alarm::HIGH_PRESSURE_TRESHOLD));

        $this->alarm->check();
        $this->assertTrue($this->alarm->alarmOn());
    }
}
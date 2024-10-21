import React, { useState, useEffect } from 'react';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';
import { useSpring, animated, config } from '@react-spring/web';
import { useInView } from 'react-intersection-observer';
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";

const initialData = [
  { name: 'Job Placements', value: 0 },
  { name: 'User Satisfaction', value: 0 },
  { name: 'Career Growth', value: 0 },
  { name: 'Network Expansion', value: 0 },
];

const finalData = [
  { name: 'Job Placements', value: 85 },
  { name: 'User Satisfaction', value: 92 },
  { name: 'Career Growth', value: 78 },
  { name: 'Network Expansion', value: 88 },
];

const AnimatedBar = animated(Bar);

const PlatformPerformanceChart = () => {
  const [data, setData] = useState(initialData);
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  });

  const headerSpring = useSpring({
    opacity: inView ? 1 : 0,
    transform: inView ? 'translateY(0px)' : 'translateY(50px)',
    config: config.molasses,
  });

  const chartSpring = useSpring({
    opacity: inView ? 1 : 0,
    config: config.molasses,
  });

  useEffect(() => {
    if (inView) {
      const timer = setTimeout(() => {
        setData(finalData);
      }, 500);
      return () => clearTimeout(timer);
    }
  }, [inView]);

  return (
    <section className="bg-black py-16">
      <div className="container mx-auto px-4">
        <animated.div style={headerSpring}>
          <h2 className="text-4xl font-bold text-white mb-12 text-center">
            Platform Performance
          </h2>
        </animated.div>
        <Card className="bg-slate-950 text-white border-gray-800" ref={ref}>
          <CardHeader>
            <CardTitle className="text-2xl font-bold">Key Metrics</CardTitle>
          </CardHeader>
          <CardContent>
            <animated.div style={chartSpring} className="h-[400px]">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={data} layout="vertical">
                  <CartesianGrid strokeDasharray="3 3" stroke="#374151" />
                  <XAxis type="number" domain={[0, 100]} tick={{fill: '#9CA3AF'}} />
                  <YAxis dataKey="name" type="category" tick={{fill: '#9CA3AF'}} />
                  <Tooltip 
                    contentStyle={{backgroundColor: '#1F2937', border: 'none'}}
                    itemStyle={{color: '#D1D5DB'}}
                  />
                  <AnimatedBar 
                    dataKey="value" 
                    fill="#3B82F6" 
                    animationDuration={2000}
                    label={{fill: '#D1D5DB', fontSize: 12, position: 'right'}}
                  />
                </BarChart>
              </ResponsiveContainer>
            </animated.div>
          </CardContent>
        </Card>
      </div>
    </section>
  );
};

export default PlatformPerformanceChart;